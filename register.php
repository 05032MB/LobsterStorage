<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>REGISTER</title>
<link rel="stylesheet" type="text/css" href="media/style/main.css">
<link rel="stylesheet" type="text/css" href="media/style/alerts.css">
<script src="js/ajax.js"></script>

<?php
	
	$error = '';
	$show1 = "display:none;"; //for now we dont need error message
	
	if(isset($_POST['register']))
	{
		if(isset($_POST['login']) && isset($_POST['password1']) && isset($_POST['password2']))
		{
	
		}else{
			$error = "Puste pola formularza!";
			$show1 = "display:block;";
		}
	}

?>
</head>
<body>

<?php
include('menu.php');
?>

<div class="notifArea">
	<div style = "<?php echo $show1 ?>">
		<div class="alert error">
			<span class="closebtn">&times;</span> 
			<strong>Failed to register. </strong><?php echo $error ?>
		</div>
	</div>
</div>

<script src="js/alertutil.js"></script>

<div class="defContainer">
<h1>Register</h1>
<fieldset>
<form action="register.php" method="POST">
Login:<br /><input type="text" name="login" onkeyup="checkIfAvailable(this)" required><br />
E-mail:<br /><input type="text" name="email"><br />
Password:<br /><input type="password" name="password1" required><br />
Repeat password:<br /><input type="password" name="password2" required><br />
<input type="submit" name="register" value="Register"><br />
</form>
</fieldset>

</div>

<script type="text/javascript">

</script>
</body>
</html>