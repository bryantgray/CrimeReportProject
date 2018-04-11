<?php
   include('session.php');
?>
<html">

   <head>
      <title>Welcome</title>
   </head>

   <body>
      <h1>Welcome <?php echo $login_session; ?></h1>
      <h2><a href = "logout.php">Sign Out</a></h2>
      <h2><a href = "EditAccount.php">Edit Account</a></h2>
      <h2><a href = "map.php">Map</a></h2>
   </body>
</html>

