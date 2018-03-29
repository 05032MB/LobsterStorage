<!DOCTYPE html>
<html>
<head>
<title>PROFILE</title>
<meta charset="UTF-8" />
<link rel="stylesheet" type="text/css" href="media/style/main.css">
<link rel="stylesheet" type="text/css" href="media/style/alerts.css">
<?php
require('session.php');
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

if(!empty($login_session)){
echo "<a class=\"userIco\" href=\"profile.php\"> ".$login_session."&nbsp"."</a>";
printf("<span class=\"right\"><a class=\"logout\" href =\"logout.php\" >Logout</a></span><!--span->div -->");

}

?>
</div>
</div>

</body>
</html>