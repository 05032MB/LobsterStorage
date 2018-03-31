<?php

require_once('settings.php');

$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);

$db = mysql_select_db(Settings::databaseName, $connection);
session_start();

$user_check=$_SESSION['login_user'];
$userId;

$ses_sql=mysql_query("SELECT username FROM login WHERE username='$user_check'", $connection);
$row = mysql_fetch_assoc($ses_sql);
$login_session =$row['username'];

if(!isset($login_session)){
mysql_close($connection); 
$error = 'Musisz się zalogować matole';
header('Location: login.php'); 
}else{

$ses_sql=mysql_query("SELECT username, ugroup, id FROM login WHERE username='$user_check'", $connection);
$row = mysql_fetch_assoc($ses_sql);
$group =$row['ugroup'];
$userId = $row['id'];

//$_SESSION['ugroup'] = $group;
//$_SESSION['userId'] = $userId;

mysql_close($connection); 
}
?>