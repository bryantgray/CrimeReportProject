<?php
   include("config.php");
   include('session.php');
   session_start();
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form
      $mypassword = mysqli_real_escape_string($db,$_POST['password']);
      $myfirstname = mysqli_real_escape_string($db,$_POST['firstname']);
      $mylastname = mysqli_real_escape_string($db,$_POST['lastname']);
      $sql = "UPDATE userprofile SET firstname='$myfirstname', lastname='$mylastname', password='$mypassword' WHERE username='$login_session'";
      if(mysqli_query($db,$sql) == TRUE)
      {
        $_SESSION['login_user'] = $myusername;
        header("location: welcome.php");
      }
      else {
         echo $error;
      }
   }
?>
<html>

   <head>
      <title>Edit Account</title>

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
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Edit Account</b></div>

            <div style = "margin:30px">

               <form action = "" method = "post">
                  <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
                  <label>FirstName  :</label><input type = "text" name = "firstname" class = "box"/><br /><br />
                  <label>LastName  :</label><input type = "text" name = "lastname" class = "box"/><br /><br />
                  <input type = "submit" value = " Submit "/><br />
               </form>

               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>

            </div>

         </div>

      </div>

   </body>
</html>