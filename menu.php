


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
}else{
printf("<span class=\"right\"><a class=\"register\" href =\"register.php\" >Sign up</a></span><!--span->div -->");
}
?>

</div>
</div>

