package com.example.crimereportapp;

import android.content.Intent;
import android.location.Location;
import android.os.Debug;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.SimpleCursorAdapter;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class CrimeInfoActivity extends AppCompatActivity {

    private ListView listView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_crime_info);

        this.listView = (ListView) findViewById(R.id.list);
        populateList(MapsActivity.crimes);
    }

    // Populate the listview with crime infomation
    public void populateList(final ArrayList<Crime> crimes)
    {
        final ArrayList<String> crimeTypes = new ArrayList<>();

        // Create an array of all the different types of crimes
        for(Crime c : crimes)
        {
            if(!crimeTypes.contains(c.getCrimeType()))
                crimeTypes.add(c.getCrimeType());
        }

        final int [] crimeFreq = new int [crimeTypes.size()];
        // Create and array to hold how many instances of each crime type there are
        for(int i = 0; i < crimes.size(); i++)
        {
            crimeFreq[crimeTypes.indexOf(crimes.get(i).getCrimeType())]++;
        }

        // Create list of crimes and crime counts to place in list
        List<Map<String, String>> crimeCounts = new ArrayList<>();
        for(int i = 0; i < crimeTypes.size(); i++)
        {
            Map<String, String> map = new HashMap<>();
            map.put("title", crimeTypes.get(i));
            map.put("data", String.valueOf(crimeFreq[i]));
            crimeCounts.add(map);
        }

        // Create adapter to place data in listview
        SimpleAdapter adapter = new SimpleAdapter(this, crimeCounts,
            android.R.layout.simple_list_item_2,
            new String[] {"title", "data"},
            new int[] {android.R.id.text1,
                    android.R.id.text2,
        });

        listView.setAdapter(adapter);
    }

    // End this activity to it may be updated next time
    @Override
    public void onBackPressed() {
        CrimeInfoActivity.this.finish();
        super.onBackPressed();
    }
}
