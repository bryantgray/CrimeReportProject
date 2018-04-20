package com.example.crimereportapp;

import android.Manifest;
import android.app.AlertDialog;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.ProgressDialog;
import android.content.ComponentName;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.ServiceConnection;
import android.content.pm.PackageManager;
import android.graphics.Color;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.os.IBinder;
import android.support.v4.app.ActivityCompat;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.NotificationCompat;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.SearchView;
import android.widget.Toast;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.Circle;
import com.google.android.gms.maps.model.CircleOptions;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NamedNodeMap;
import org.w3c.dom.NodeList;
import org.xml.sax.InputSource;
import org.xml.sax.SAXException;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.StringReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;

public class MapsActivity extends FragmentActivity implements OnMapReadyCallback
{
    private Button myLocationBtn, newLocationBtn;

    private GoogleMap map;
    private Location location;
    public static LatLng myLatLng;
    public static ArrayList<Crime> crimes;
    private int searchRadius; // In meters
    private Circle circle;

    private LocationManager locationManager;
    private ArrayList<Marker> markers;

    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_maps);
        SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager()
                .findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);

        locationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);

        // New location button
        newLocationBtn = (Button) findViewById(R.id.new_location);

        // My location button
        myLocationBtn = findViewById(R.id.my_location_btn);
        myLocationBtn.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View view) {
                updateMap(myLatLng);
            }
        });
    }

    @Override
    public void onMapReady(GoogleMap googleMap)
    {
        map = googleMap;
        map.setOnMapClickListener(new GoogleMap.OnMapClickListener() {
            @Override
            public void onMapClick(final LatLng latLng) {

                AlertDialog.Builder builder = new AlertDialog.Builder(MapsActivity.this);
                builder.setTitle("Search This Location For Crimes?");
                builder.setPositiveButton("yes", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {
                        myLatLng = latLng;
                        updateMap(myLatLng);
                        dialogInterface.dismiss();
                    }
                });

                builder.setNegativeButton("no", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {
                        dialogInterface.dismiss();
                    }
                });

                AlertDialog dialog = builder.create();
                dialog.show();
            }
        });

        markers = new ArrayList<>();
        searchRadius = 1000;

        getCurrentLocation();

        // Use a default location if location services are not working correctly
        if(myLatLng == null)
            myLatLng = new LatLng(28.602566, -81.200866);

        addUserAndCircle(myLatLng);

        // Update camera settings
        map.setMinZoomPreference(5);
        map.setMaxZoomPreference(20);
        map.getUiSettings().setRotateGesturesEnabled(false);

        updateCamera(myLatLng);

        getCrimesFromServer();
    }

    // Update the camera position
    private void updateCamera(LatLng location)
    {
        map.animateCamera(CameraUpdateFactory.newLatLngZoom(location, 14), 1500, null);
    }

    // Find users new location, and update map with new crimes
    public void updateMap(LatLng location)
    {
        getCurrentLocation();
        map.clear();
        addUserAndCircle(location);
        populateMapWithCrimes(crimes);
        updateCamera(location);
    }

    // Adds a circle around the user on the map
    private void addUserAndCircle(LatLng location)
    {
        circle = map.addCircle(new CircleOptions().center(location)
                .radius(searchRadius)
                .strokeColor(Color.BLUE));
    }

    private LocationListener mLocationListener = new LocationListener()
    {
        @Override
        public void onLocationChanged(Location location) {
            if (location != null) {
                Log.d("updateMap",location.getLatitude() + " " + location.getLongitude());
                locationManager.removeUpdates(mLocationListener);
            }
        }

        @Override
        public void onStatusChanged(String s, int i, Bundle bundle) {
        }

        @Override
        public void onProviderEnabled(String s) {
        }

        @Override
        public void onProviderDisabled(String s) {
        }
    };

    // Gets the users current location based on network and gps location info
    private void getCurrentLocation()
    {
        boolean isNetworkOn = locationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER);
        boolean isGPSOn = locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER);

        // Show error message if connection to network or gps could not be made
        if (!isNetworkOn || !isGPSOn)
            return;

        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED
            && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED)
        {
            int retVal = 0;
            int [] results = {};

            ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, retVal);

            onRequestPermissionsResult(retVal,
                    new String[]{Manifest.permission.ACCESS_FINE_LOCATION},
                    results);

            return;
        }
        locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER,
                5000, 10, mLocationListener);

        location = locationManager.getLastKnownLocation(LocationManager.GPS_PROVIDER);
        myLatLng = new LatLng(location.getLatitude(), location.getLongitude());
    }

    // Add crimes to the map, and update the circle color
    public void populateMapWithCrimes(ArrayList<Crime> crimes)
    {
        // Add nearby crimes to the map
        int numNearbyCrimes = 0;
        for(Crime crime : crimes)
        {
            boolean crimeNearby = locationIsInCircle(crime.getLocation());
            if(crimeNearby)
            {
                numNearbyCrimes++;

                markers.add(map.addMarker(new MarkerOptions()
                        .position(crime.getLocation())
                        .title(crime.getCrimeType())
                        .alpha(1f)
                        .snippet(crime.getDateTime())
                        .icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_RED))));
            }
        }

        // Change the circle color depending on the crime concentration in the area
        if(numNearbyCrimes < 4)
        {
            circle.setFillColor(0x1A00FF00);
            circle.setStrokeColor(Color.GREEN);
        }
        else
        {
            circle.setFillColor(0x1AFF0000);
            circle.setStrokeColor(Color.RED);
            showWarning();
        }
    }

    // Show a notification alerting user that they have entered a dangerous area
    public void showWarning()
    {
        NotificationCompat.Builder builder  = new NotificationCompat.Builder(this, "M_CH_ID")
                .setSmallIcon(R.drawable.ic_stat_name)
                .setContentTitle("Warning")
                .setContentText("Entered A Dangerous Area")
                .setPriority(NotificationCompat.PRIORITY_DEFAULT);

        NotificationManager notificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);

        // Create a notification channel if version > oreo
        if(Build.VERSION.SDK_INT >= Build.VERSION_CODES.O)
        {
            NotificationChannel channel = new NotificationChannel("M_CH_ID", "testName", NotificationManager.IMPORTANCE_DEFAULT);
            notificationManager.createNotificationChannel(channel);
        }

        notificationManager.notify(0, builder.build());
    }

    // Returns whether or not a given location is within the nearby circle
    public boolean locationIsInCircle(LatLng location)
    {
        float [] results = new float[1];
        Location.distanceBetween(circle.getCenter().latitude, circle.getCenter().longitude, location.latitude, location.longitude, results);

        return (results[0] <= searchRadius);
    }

    // Create crimes based on xml input
    public void parseDatabaseCrimes(String xml)
    {
        DocumentBuilder docBuilder = null;
        try {
            docBuilder = DocumentBuilderFactory.newInstance().newDocumentBuilder();
            Document parse = docBuilder.parse(new InputSource(new StringReader(xml)));

            NodeList markers = parse.getElementsByTagName("markers");
            Element element = (Element) markers.item(0);
            NodeList markerList = element.getElementsByTagName("marker");

            // Parse crimes xml and update crimes list
            crimes = new ArrayList<>();
            for(int i = 0; i < markerList.getLength(); i++)
            {
                NamedNodeMap nodeMap = markerList.item(i).getAttributes();
                String lat = nodeMap.getNamedItem("lat").getNodeValue();
                String lng = nodeMap.getNamedItem("lng").getNodeValue();
                String type = nodeMap.getNamedItem("type").getNodeValue();
                String date = nodeMap.getNamedItem("date").getNodeValue();

                crimes.add(new Crime(lat, lng, type, date));
            }

            updateMap(myLatLng);

        } catch (ParserConfigurationException e) {
            e.printStackTrace();
        } catch (SAXException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    // Begin retrieving crimes from server
    public void getCrimesFromServer()
    {
        new AsyncLogin().execute();
    }

    // Update map button pressed
    public void updateMap(View view)
    {
        updateMap(myLatLng);
    }

    // Go to the add crime info activity
    public void crimeInfoBtn(View view) {
        Intent intent = new Intent(MapsActivity.this,CrimeInfoActivity.class);
        startActivity(intent);
    }

    // Go to the edit account activity
    public void editAccount(View view)
    {
        Intent intent = new Intent(MapsActivity.this,EditActivity.class);
        startActivity(intent);
    }

    // Go to the add crime activity
    public void addCrime(View view)
    {
        Intent intent = new Intent(MapsActivity.this,AddCrimeActivity.class);
        startActivity(intent);
    }

    private class AsyncLogin extends AsyncTask<String, String, String>
    {
        HttpURLConnection conn;
        URL url = null;

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
        }
        @Override
        protected String doInBackground(String... params) {
            try {
                // URL of the php file - Note: 10.0.2.2 is used to allow emulator to connect
                url = new URL("http://10.0.2.2/login/get_crimes.php");
            } catch (MalformedURLException e) {
                e.printStackTrace();
                return "exception";
            }
            try {
                // Establish connection with the sql server
                conn = (HttpURLConnection)url.openConnection();
                conn.setReadTimeout(10000);
                conn.setConnectTimeout(15000);
                conn.setRequestMethod("GET");
                conn.connect();

            } catch (IOException e1) {
                e1.printStackTrace();
                return "exception";
            }

            try {

                int response_code = conn.getResponseCode();

                // Check if successful connection made
                if (response_code == HttpURLConnection.HTTP_OK) {

                    // Read data sent from server
                    InputStream input = conn.getInputStream();
                    BufferedReader reader = new BufferedReader(new InputStreamReader(input));
                    StringBuilder result = new StringBuilder();
                    String line;

                    while ((line = reader.readLine()) != null) {
                        result.append(line);
                    }

                    // Pass data to onPostExecute method
                    return(result.toString());

                }else{

                    return("unsuccessful");
                }

            } catch (IOException e) {
                e.printStackTrace();
                return "exception";
            } finally {
                conn.disconnect();
            }
        }

        @Override
        protected void onPostExecute(String result)
        {
            // Update the crimes list with the most recent database info
            parseDatabaseCrimes(result);
        }
    }
}
