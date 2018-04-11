<?php
   include('session.php');
   include('Config.php');

// Gets data from URL parameters.
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$type = $_GET['type'];
$date = $_GET['date'];
$verified = $_GET['verified'];

// Opens a connection to a MySQL server.
// $connection=mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
// if (!$connection) {
//   die('Not connected : ' . mysqli_error());
// }

// Sets the active MySQL database.
$db_selected = mysqli_select_db($db, DB_DATABASE);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysqli_error());
}

// Inserts new row with place data.
$query = sprintf("INSERT INTO crimes " .
         " (id, lat, lng, type, date, verified ) " .
         " VALUES (NULL, '%s', '%s', '%s', '%s', '%s');",
         mysqli_real_escape_string($db,$lat),
         mysqli_real_escape_string($db,$lng),
         mysqli_real_escape_string($db,$type),
         mysqli_real_escape_string($db,$date),
         mysqli_real_escape_string($db,$verified));

$result = mysqli_query($db,$query);

if (!$result) {
  die('Invalid query: ' . mysqli_error());
}

?>