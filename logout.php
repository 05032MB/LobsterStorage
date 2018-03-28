<!DOCTYPE html>

<html>

<head>
<meta charset="UTF-8" />
<title>LOGOUT</title>
<link rel="stylesheet" type="text/css" href="main.css">
<link rel="stylesheet" type="text/css" href="main.css">
<link rel="stylesheet" type="text/css" href="alerts.css">

</head>

<body>

<div class="notifArea">

</div>

 <script src="alertutil.js" ></script>
 <div class="login">




<?php
session_start();
if(session_destroy()) // Destroying All Sessions
{

echo "
<div class=\"alert success\">
  <span class=\"closebtn\">&times;</span> <!--X--> 
  <strong>Logout successful !</strong>";

}else{
echo "
<div class=\"alert error\">
  <span class=\"closebtn\">&times;</span> <!--X--> 
  <strong>Logout failed! </strong>Go kill yourself.
</div>";

}
?>

<a href = "login.php">Zaloguj ponownie</a>

</div>
</body>

</html>