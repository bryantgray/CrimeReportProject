<?php
   include("config.php");
   session_start();
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form
      $mypassword = mysqli_real_escape_string($db,$_POST['password']);
      $myfirstname = mysqli_real_escape_string($db,$_POST['firstname']);
      $mylastname = mysqli_real_escape_string($db,$_POST['lastname']);
      $username = mysqli_real_escape_string($db,$_POST['username']);
      $sql = "UPDATE userprofile SET firstname='$myfirstname', lastname='$mylastname', password='$mypassword' WHERE username='$username'";
      if(mysqli_query($db,$sql) == TRUE)
      {
         echo "true";
      }
      else {
         echo $error;
      }
   }
?>
