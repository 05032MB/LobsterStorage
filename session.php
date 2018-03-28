<?php

require_once('settings.php');

$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);

$db = mysql_select_db(Settings::databaseName, $connection);
session_start();

$user_check=$_SESSION['login_user'];

$ses_sql=mysql_query("select username from login where username='$user_check'", $connection);
$row = mysql_fetch_assoc($ses_sql);
$login_session =$row['username'];

if(!isset($login_session)){
mysql_close($connection); 
$error = 'Musisz się zalogować matole';
header('Location: login.php'); 
}else{

$ses_sql=mysql_query("SELECT username, ugroup FROM login WHERE username='$user_check'", $connection);
$row = mysql_fetch_assoc($ses_sql);
$group =$row['ugroup'];

$_SESSION['ugroup'] = $group;

mysql_close($connection); 
}
?>