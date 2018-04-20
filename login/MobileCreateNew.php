<?php
   include("config.php");
   // session_start();

   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form

      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']);
      $myfirstname = mysqli_real_escape_string($db,$_POST['firstname']);
      $mymiddlename = mysqli_real_escape_string($db,$_POST['middlename']);
      $mylastname = mysqli_real_escape_string($db,$_POST['lastname']);

      $sql = "INSERT INTO userprofile (username, password, firstname, middlename, lastname) VALUES ('$myusername', '$mypassword', '$myfirstname', '$mymiddlename', '$mylastname')";
      if(mysqli_query($db,$sql) == TRUE)
      {
        echo "true";
      }
      else {
         echo "false";
      }
   }
?>