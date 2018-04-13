<?php
   include('session.php');
?>

<html>

   <head>
      <title>Welcome</title>
   </head>

   <body>
      
         <h1>Welcome <?php echo $login_session; ?></h1>
      <div class='tab'>
         <h3><a href = "map.php">Map</a></h3>
         <h3><a href = "EditAccount.php">Edit account</a></h3>
         <h3><a href = "logout.php">Sign Out</a></h3>
      </div>
   </body>
</html>

