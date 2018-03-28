<!DOCTYPE html>

<html>

<head>
<meta charset="UTF-8" />
<title>LOGIN</title>
<link rel="stylesheet" type="text/css" href="main.css">
<link rel="stylesheet" type="text/css" href="alerts.css">

<?php

include('settings.php');
include('fileUtil.php');

$error='';
session_start(); // Starting Session
 // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['login']) || empty($_POST['password'])) {
$error = '';
}
else
{
// Define $username and $password
$username=$_POST['login'];
$password=$_POST['password'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);
// To protect MySQL injection for Security purpose
$username = sanitize($username);
$password = sanitize($password);
$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);
echo "<script>console.log('$username')</script>";
//echo $username;
// Selecting Database
$db = mysql_select_db(Settings::databaseName, $connection);
// SQL query to fetch information of registerd users and finds user match.
$query = mysql_query("select * from login where password='$password' AND username='$username'", $connection);
$rows = mysql_num_rows($query);
if ($rows == 1) {
$_SESSION['login_user']=$username; // Initializing Session
header("location: files.php"); // Redirecting To Other Page
} else {
$error = "Username or Password is invalid";

}
mysql_close($connection); // Closing Connection



}
}
?>
<?php
$name ='';
$show1 ='display:none;';

if(isset($_SESSION['login_user'])){
$name = $_SESSION['login_user'];
}

//echo $error;
if (!empty($error))$show1 = 'visibility:visible;';
//var_dump($error);


?>
</head>

<body>
<div class="menuArea">
<a href="login.php">Home &nbsp </a>
<a href="files.php">Files &nbsp </a>
Profile &nbsp
Misc &nbsp

<div class="right">

<?php

if(!empty($name)){
echo "<a class=\"userIco\" href=\"profile.php\"> ".$name."&nbsp"."</a>";
printf("<span class=\"right\"><a class=\"logout\" href =\"logout.php\" >Logout</a></span><!--span->div -->");

}

?>
</div>
</div>

<div class="notifArea">
<!--<div class="alert warning">
  <span class="closebtn">&times;</span> <!--X--> 
<!--  <strong>Warning? </strong>Warning!
</div>-->

<div style = "<?php echo $show1 ?>">

<div class="alert error">
  <span class="closebtn">&times;</span> <!--X--> 
  <strong>Failed to login. </strong><?php echo $error ?>
</div>

</div>

</div>

 <script src="alertutil.js" ></script>
 <div class="login">

 <form method="POST" action="login.php" >
 
 <label>Login:</label>
 <p><input type="text" name="login" id="login" />
 <p><label>Password:</label>
 <p><input type="password" name="password" id="password" />
 
 <!--<p><button>
 Login
 </button>-->
 <p><input name="submit" type="submit" value=" Login ">
 
 </form>

</div>
</body>

</html>