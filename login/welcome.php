<?php
   include('session.php');
?>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://fonts.googleapis.com/css?family=Lato:300,900" rel="stylesheet">
  <title>Document</title>
</head>
<body>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="welcome.php">Crime Report</a>
        </div>
      <ul class="nav navbar-nav">
        <li class="active"><a href="welcome.php">Home</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
      <li><a href="EditAccount.php"><span class="glyphicon glyphicon-user"></span> Edit Account</a></li>
      <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Sign Out</a></li>
    </ul>
    </div>
  </nav>
</body>
</html>

<style>
  
  p {
    font-family: 'Lato', sans-serif;
    font-weight:300;
    font-size:45px;
  }

  p b {
    font-family: 'Lato', sans-serif;
    font-weight:900;
    font-size:45px;
  }

</style>

<html>

   <head>
      <title>Welcome</title>
   </head>

   <body>
      
      <div class="container-fluid">
         <p><b>Welcome</b> <?php echo $login_session; ?></p>
      </div>
      
      <div class="container-fluid">
      <div class='row'>
          <iframe class='col-lg-8' height="600px" width="60%" frameborder="0" scrolling="no" src="map.php" allowfullscreen="">
            <div id="map" style="position: relative; overflow: hidden;"></div>
          </iframe>
        
        <div class='col-md-4' id='table'>
          <table class='table table-hover'>
            <tr>
              <th>Type</th>
              <th>Number of crimes</th>
            </tr>
            <tr>
              <td id='assault'>Assault</td><td id='numAssault'></td>
            </tr>
            <tr>
              <td id='burglary'>Burglary</td><td id='numBurglary'></td>
            </tr>
            <tr>
              <td id='mugging'>Mugging</td><td id='numMugging'></td>
          </table>
        </div>

        <div class='col-md-2 col-md-offset-2'>
          <img src='wrw16.gif' style='width:100%; position:absolute; top:200px; left:0px;'>
        </div>

      </div>
</div>

   </body>
</html>

<script>
   
   
      

   countTypes();

   function countTypes() {
          
      downloadUrl('get_crimes.php', function(data) {
         var xml = data.responseXML;
          var markers = xml.getElementsByTagName('marker');
          var numMarkers = markers.length;
          var burglaryCount = 0;
          var assaultCount = 0;
          var muggingCount = 0;

          console.log("Markers: " + numMarkers);

          for (var i = 0; i < numMarkers; i++) {
            if (markers[i].getAttribute('type') == 'Burglary') {
              burglaryCount++;
            }
            if (markers[i].getAttribute('type') == 'Assault') {
              assaultCount++;
            }
            if (markers[i].getAttribute('type') == 'Mugging') {
              muggingCount++;
            }
          }

          document.getElementById('numBurglary').innerHTML = burglaryCount;
          document.getElementById('numAssault').innerHTML = assaultCount;
          document.getElementById('numMugging').innerHTML = muggingCount;
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

   function doNothing(){

   }
</script>

