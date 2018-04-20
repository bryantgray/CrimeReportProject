package com.example.crimereportapp;

import android.content.Intent;
import android.net.Uri;
import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;

public class AddCrimeActivity extends AppCompatActivity {

    private EditText crimeText;
    private Button submitBtn;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_add_crime);

        crimeText = (EditText) findViewById(R.id.crimeType);
        submitBtn = (Button) findViewById(R.id.submitCrime);
    }

    // Submit button pressed
    public void submit(View view)
    {
        final double lat = MapsActivity.myLatLng.latitude;
        final double lng = MapsActivity.myLatLng.longitude;
        final String crime = crimeText.getText().toString();
        final Boolean verified = false;

        SimpleDateFormat formatter = new SimpleDateFormat("MM/dd/yyyy");
        Date dateTime = Calendar.getInstance().getTime();
        final String date = formatter.format(dateTime);

        // Add crime to server
        new AsyncAddCrime().execute(String.valueOf(lat),String.valueOf(lng), crime, date, String.valueOf(verified));
    }

    private class AsyncAddCrime extends AsyncTask<String, String, String>
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
                url = new URL("http://10.0.2.2/login/mobile_crime_addrow.php");

            } catch (MalformedURLException e) {
                e.printStackTrace();
                return "exception";
            }
            try {
                // Establish connection with the sql server
                conn = (HttpURLConnection)url.openConnection();
                conn.setReadTimeout(10000);
                conn.setConnectTimeout(15000);
                conn.setRequestMethod("POST");

                // Handle send and recieves
                conn.setDoInput(true);
                conn.setDoOutput(true);

                // Build up the query
                Uri.Builder builder = new Uri.Builder()
                        .appendQueryParameter("lat", params[0])
                        .appendQueryParameter("lng", params[1])
                        .appendQueryParameter("type", params[2])
                        .appendQueryParameter("date", params[3])
                        .appendQueryParameter("verified", params[4]);
                String query = builder.build().getEncodedQuery();

                // Open connection for sending data
                OutputStream os = conn.getOutputStream();
                BufferedWriter writer = new BufferedWriter(
                        new OutputStreamWriter(os, "UTF-8"));
                writer.write(query);
                writer.flush();
                writer.close();
                os.close();
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
            Toast.makeText(AddCrimeActivity.this, "Reported Crime", Toast.LENGTH_LONG).show();

            Intent intent = new Intent(AddCrimeActivity.this,MapsActivity.class);
            startActivity(intent);
            AddCrimeActivity.this.finish();
        }
    }
}
