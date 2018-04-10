<?php
   include("config.php");
   session_start();
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
        $_SESSION['login_user'] = $myusername;
        header("location: welcome.php");
      }
      else {
         $error = "USERNAME TAKEN xD ";
      }
   }
?>
<html>

   <head>
      <title>Register</title>

      <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         .box {
            border:#666666 solid 1px;
         }
      </style>

   </head>

   <body bgcolor = "#FFFFFF">

      <div align = "center">
         <div style = "width:300px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Register</b></div>

            <div style = "margin:30px">

               <form action = "" method = "post">
                  <label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />
                  <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
                  <label>FirstName  :</label><input type = "text" name = "firstname" class = "box"/><br /><br />
                  <label>MiddleName  :</label><input type = "text" name = "middlename" class = "box"/><br /><br />
                  <label>LastName  :</label><input type = "text" name = "lastname" class = "box"/><br /><br />
                  <input type = "submit" value = " Submit "/><br />
               </form>

               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>

            </div>

         </div>

      </div>

   </body>
</html>