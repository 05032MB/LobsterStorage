<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>REGISTER</title>
<link rel="stylesheet" type="text/css" href="media/style/main.css">
<link rel="stylesheet" type="text/css" href="media/style/alerts.css">
<script src="js/ajax.js"></script>

<?php
	
	require_once "settings.php";
	require_once "fileUtil.php";
	
	$error = '';
	$success = '';
	$show1 = "display:none;"; //for now we dont need error message
	$show2 = "display:none";
	
	if(isset($_POST['register']))
	{
	//echo 'n';
		if(isset($_POST['login']) && isset($_POST['password1']) && isset($_POST['password2']))
		{
			$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);
			$db = mysql_select_db(Settings::databaseName, $connection);
			
			$login = mysql_real_escape_string(sanitize($_POST['login']));
			$password1 = mysql_real_escape_string(sanitize($_POST['password1']));
			$password2 = mysql_real_escape_string(sanitize($_POST['password2']));
			
			$query = mysql_query("SELECT id, username FROM login WHERE username='$login'");
			
			/*$mysqlF = mysql_error($connection);
			if($mysqlF)
			{
				$error = $mysqlF;
			}*/
			
			if(mysql_num_rows($query) === 0)
			{
				if($password1 == $password2)
				{
					$query = mysql_query("INSERT INTO login(username, password, ugroup) VALUES ('$login', '$password1', 5)");	//plaintext LOL
					$success = 'Registration complete!';
				}
				else{
					$error = "Passwords do not match.";
				}
			}else
			{
				$error = 'Username is already taken';
			}
			
			mysql_close($connection);
			
		}else{
			$error = "Puste pola formularza!";
			$show1 = "display:block;";
		}
	}
	
	if(!empty($error))
	{
		$show1 = "display:block;";
	}
	if(!empty($success))
	{
		$show2 = "display:block;";
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
	
	<div style = "<?php echo $show2 ?>">
		<div class="alert success">
			<span class="closebtn">&times;</span> 
			<?php echo $success ?>
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