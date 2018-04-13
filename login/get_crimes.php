<?php

require("Config.php");

// Start XML file, create parent node

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Opens a connection to a MySQL server

// $connection=mysql_connect ('localhost', $username, $password);
// if (!$connection) {  die('Not connected : ' . mysql_error());}

// Set the active MySQL database

$db_selected = mysqli_select_db($db, DB_DATABASE);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysqli_error());
}

// Select all the rows in the markers table

$query = "SELECT * FROM crimes WHERE 1";
$result = mysqli_query($db,$query);
if (!$result) { 
  die('Invalid query: ' . mysqli_error());
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each

while ($row = @mysqli_fetch_assoc($result)){
  // Add to XML document node
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("id",$row['id']);
  $newnode->setAttribute("lat",$row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
  $newnode->setAttribute("type", $row['type']);
  $newnode->setAttribute("date", $row['date']);
  $newnode->setAttribute("verified", $row['verified']);
}

echo $dom->saveXML();


?>