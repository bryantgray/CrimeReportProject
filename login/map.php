<html>

   <head>
      <title>Map</title>
   </head>

   <body>
	   <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title></title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 97%;
      }
      /* Optional: Makes the page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>

  <body>

    <div id="floating-panel">
      <input id="address" type="textbox" placeholder="Enter Location">
      <input id="submit" type="button" value="Search">
    </div>

    <div id="map"></div>

    <div id="form">
      <table>
      <tr><td>Type:</td> <td><select id='type'> + 
                 <option value='Assault' SELECTED>Assault</option>
                 <option value='Burglary'>Burglary</option>
                 <option value='Mugging'>Mugging</option>
                 </select> </td></tr>
                 <tr><td></td><td><input type='button' value='Save' onclick='saveData()'/></td></tr>
      </table>
    </div>

    <div id="message">Location saved</div>

    
    <!-- <div id="map"></div> -->

    
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLnVhMb29NSWC-1RMWqW_xI1Z-YBXmlUg&callback=initMap">
    </script>

    <script>
      
      var map;
      var marker;
      var infowindow;
      var messagewindow;
      var geocoder;

      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: {lat: 28.538336, lng: -81.379234 }
        });
  
        marker = new google.maps.Marker({
          map: map,
          animation: google.maps.Animation.DROP,
          icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png',
          draggable: true
        });

        infowindow = new google.maps.InfoWindow({
          content: document.getElementById('form')
        });

        messagewindow = new google.maps.InfoWindow({
          content: document.getElementById('message')
        });

        crimetable = new google.maps.InfoWindow({
          content: document.getElementById('table')
        });

        google.maps.event.addListener(map, 'click', function(e) {
          placeMarkerAndPanTo(marker, e.latLng, map);
          infowindow.open(map, marker);
        });

        // Change this depending on the name of your PHP or XML file
        downloadUrl('get_crimes.php', function(data) {
          var xml = data.responseXML;
          var markers = xml.documentElement.getElementsByTagName('marker');

          Array.prototype.forEach.call(markers, function(markerElem) {
            var id = markerElem.getAttribute('id');
            var type = markerElem.getAttribute('type');
            var date = markerElem.getAttribute('date');
            var verified = markerElem.getAttribute('verified');
            var point = new google.maps.LatLng(
                parseFloat(markerElem.getAttribute('lat')),
                parseFloat(markerElem.getAttribute('lng')));

            var infowincontent = document.createElement('div');
            var strong = document.createElement('strong');
            strong.textContent = type
            infowincontent.appendChild(strong);
            infowincontent.appendChild(document.createElement('br'));

            var text = document.createElement('text');
            text.textContent = date
            infowincontent.appendChild(text);
            // var icon = customLabel[type] || {};  
              var marker1 = new google.maps.Marker({
                map: map,
                position: point,
                // label: icon.label
              });
          
            var infowindow1 = new google.maps.InfoWindow();
            marker1.addListener('click', function() {
              infowindow1.setContent(infowincontent);
              infowindow1.open(map, marker1);
            });
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
        }

        function error(err) {
          console.warn(`ERROR(${err.code}): ${err.message}`);
        }

        navigator.geolocation.getCurrentPosition(success, error, options);
        // END GET USER LOCATION

        geocoder = new google.maps.Geocoder();

        document.getElementById('submit').addEventListener('click' , function() {
          geocodeAddress(geocoder, map);
        });  
      }

        function geocodeAddress(geocoder, resultsMap) {
          var address = document.getElementById('address').value;
          geocoder.geocode({'address': address}, function(results, status) {
            if (status === 'OK') {
              resultsMap.setCenter(results[0].geometry.location);
              var s = results[0].geometry.location;
              console.log(results[0].geometry.location);
            } else {
              alert('Geocode was not successful for the following reason: ' + status);
            }
          });
        }

      function saveData() {
          var type = document.getElementById("type").value;
          var latlng = marker.getPosition();
          var date = new Date().toISOString().slice(0, 19).replace('T', ' ');

          console.log(`lat: ${latlng.lat()}`)
          var url = "crime_addrow.php?lat=" + latlng.lat() + "&lng=" + latlng.lng() +
                    "&type=" + type + "&date=" + date + "&verified=" + 0;

          downloadUrl(url, function(data, responseCode) {
            console.log(data.length)
            console.log(url)
            if (responseCode == 200) {
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
              callback(request, request.status);
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

