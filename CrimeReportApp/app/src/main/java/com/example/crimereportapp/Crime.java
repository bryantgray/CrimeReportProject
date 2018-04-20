package com.example.crimereportapp;

import com.google.android.gms.maps.model.LatLng;

public class Crime {

    private LatLng location;
    private String dateTime;
    private String crimeType;

    public Crime(String lat, String lng, String type, String date)
    {
        this.location = new LatLng(Float.valueOf(lat), Float.valueOf(lng));
        this.crimeType = type;
        this.dateTime = date;
    }

    public LatLng getLocation() {
        return location;
    }

    public String getDateTime() {
        return dateTime;
    }

    public String getCrimeType() {
        return crimeType;
    }

    public void setCrimeType(String crimeType) {
        this.crimeType = crimeType;
    }
}
