<?php
  include('Config.php');

  // Gets data from URL parameters.
  $lat = mysqli_real_escape_string($db,$_POST['lat']);
  $lng = mysqli_real_escape_string($db,$_POST['lng']);
  $type = mysqli_real_escape_string($db,$_POST['type']);
  $date = mysqli_real_escape_string($db,$_POST['date']);
  $verified = mysqli_real_escape_string($db,$_POST['verified']);

  // Sets the active MySQL database.
  $db_selected = mysqli_select_db($db, DB_DATABASE);
  if (!$db_selected) {
    die ('Can\'t use db : ' . mysqli_error());
  }

  // Inserts new row with place data.
  $query = sprintf("INSERT INTO crimes " .
           " (id, lat, lng, type, date, verified ) " .
           " VALUES (NULL, '%s', '%s', '%s', '%s', '%s');",
           $lat, $lng, $type, $date, $verified);

  $result = mysqli_query($db,$query);

  if (!$result) {
    die('Invalid query: ' . mysqli_error());
  }

?>