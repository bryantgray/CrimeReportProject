<html">

   <head>
      <title>Map</title>
   </head>

   <body>
      <h1>Map</h1>
      <h2><a href = "welcome.php">Home</a></h2>
      <h2><a href = "logout.php">Sign Out</a></h2>
      <h2><a href = "EditAccount.php">Edit Account</a></h2>


	   <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Using MySQL and PHP with Google Maps</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 80%;
        margin: 20;
        padding: 0;
      }
    </style>
  </head>

  <body>
    <div id="map"></div>
    <div id="form">
      <table>
      <tr><td>Type:</td> <td><select id='type'> +
                 <option value='Burglary' SELECTED>Burglary</option>
                 <option value='Shooting'>Shooting</option>
                 <option value='Stabbing'>Stabbing</option>
                 <option value='Mugging'>Mugging</option>
                 </select> </td></tr>
                 <tr><td></td><td><input type='button' value='Save' onclick='saveData()'/></td></tr>
      </table>
    </div>
    <div id="message">Location saved</div>
    
 	
 	
 	
 	
 	
 	
 	
 	
 	 
    <div id="floating-panel">
  <input id="address" type="textbox" value="Sydney, NSW">
  <input id="submit" type="button" value="Geocode">
</div>
<div id="map"></div>










    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLnVhMb29NSWC-1RMWqW_xI1Z-YBXmlUg&callback=initMap">
    </script>

    <script>
      
      var map;
      var marker;
      var infowindow;
      var messagewindow;
      //ADD VARIABLE
      var geocoder = new google.maps.Geocoder();


      function initMap() {
    
  
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 10,
          center: {lat: 28.538336, lng: -81.379234 }
        });

  
        marker = new google.maps.Marker({
          map: map,
          draggable: true
        });

        infowindow = new google.maps.InfoWindow({
          content: document.getElementById('form')
        });

        messagewindow = new google.maps.InfoWindow({
          content: document.getElementById('message')
        });

        google.maps.event.addListener(map, 'click', function(e) {
          placeMarkerAndPanTo(marker, e.latLng, map);
          infowindow.open(map, marker);
          
          google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map, marker)
          });
          
        });
        

        function placeMarkerAndPanTo(marker, latLng, map) {
          marker.setPosition(latLng);
          map.panTo(latLng);
        }

        // Get User location
        var options = {
          enableHighAccuracy: false,
          timeout: 60000,
          maximumAge: Infinity
        };

        function success(pos) {
          var crd = pos.coords;
          map.setCenter({lat: crd.latitude, lng: crd.longitude});
          console.log('Your current position is:');
          console.log(`Latitude : ${crd.latitude}`);
          console.log(`Longitude: ${crd.longitude}`);
          console.log(`More or less ${crd.accuracy} meters.`);

        }
        
        var geocoder = new google.maps.Geocoder();

//ADD CODE
  document.getElementById('submit').addEventListener('click', function() {
    geocodeAddress(geocoder, map);
  });
//STOP
        function error(err) {
          console.warn(`ERROR(${err.code}): ${err.message}`);
        }

        navigator.geolocation.getCurrentPosition(success, error, options);
        // END GET USER LOCATION

      }

//ADD FUNCTION
		function geocodeAddress(geocoder, resultsMap) {
  var address = document.getElementById('address').value;
  geocoder.geocode({'address': address}, function(results, status) {
    if (status === 'OK') {
      resultsMap.setCenter(results[0].geometry.location);
      var marker = new google.maps.Marker({
        map: resultsMap,
        position: results[0].geometry.location
      });
    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}
//STOP HERE

      function saveData() {
          var type = document.getElementById("type").value;
          var latlng = marker.getPosition();
          var date = new Date().toISOString().slice(0, 19).replace('T', ' ');

          console.log(`lat: ${latlng.lat()}`)
          var url = "crime_addrow.php?lat=" + latlng.lat() + "&lng=" + latlng.lng() +
                    "&type=" + type + "&date=" + date + "&verified=" + 0;

          downloadUrl(url, function(data, responseCode) {
            console.log(data.length)
            console.log(date)
            if (responseCode == 200 && data.length <= 1) {
              infowindow.close();
              messagewindow.open(map, marker);
            }
          });
        }

        function downloadUrl(url, callback) {
          var request = window.ActiveXObject ?
              new ActiveXObject('Microsoft.XMLHTTP') :
              new XMLHttpRequest;

          request.onreadystatechange = function() {
            if (request.readyState == 4) {
              request.onreadystatechange = doNothing;
              callback(request.responseText, request.status);
            }
          };

          request.open('GET', url, true);
          request.send(null);
        }

        function doNothing () {
        }
      
    
    </script>
    
  </body>
   </body>

</html>

