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
  <link href="https://fonts.googleapis.com/css?family=Lato:300i,900" rel="stylesheet">
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