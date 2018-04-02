<!DOCTYPE html>

<html>

<head>
<meta charset="UTF-8" />
<title>FILES</title>
<link rel="stylesheet" type="text/css" href="media/style/main.css" />
<!--<link rel="stylesheet" type="text/css" href="main.css" />-->
<link rel="stylesheet" type="text/css" href="media/style/alerts.css">
</head>
<body onload="fileListUpdate()">
<?php
require_once('session.php');
require_once('fileUtil.php');
include('menu.php');

$error_msg = '';
$success = "";

$size_limit = 500000;


if(isset($_FILES['file']))
{
$target_path = "up/";
$discName = /*rand() . time();*/ uniqid("f", true);
$target_file = $target_path . $discName;

$filenam = mysql_real_escape_string(sanitize(basename( $_FILES['file']['name'])));

if(file_exists($target_file)){
	$error_msg='Critical system error. Retry and pray';
}
if($_FILES['file']['size'] > $size_limit){
	$error_msg = 'Plik jest za duży';
}	
//echo getFileType($_FILES['file']['name']);
if(getFileType($_FILES['file']['name']) == "NotAllowed")
{
	$error_msg='Niedozwolony format';
}
if(empty($_FILES['file']['name'])){
	$error_msg= 'Nie wybrano pliku';
}
if(empty($error_msg)){
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $success = "The file ".$filenam. " has been uploaded.";
		
		$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);
		$db = mysql_select_db(Settings::databaseName, $connection);
		
		//$user = $_SESSION['login_user'];
		$type = getExtension(basename($_FILES['file']['name']));
		
		
		$sqlcom = mysql_query("INSERT INTO files(fileId, userId, name, discName, type) VALUES (NULL, '$userId','$filenam','$discName','$type')", $connection);
		
		echo mysql_error($connection);
		
		mysql_close($connection);
		
    }else {
	$error_msg = 'Błąd serwera...';
	print_r($_FILES);
}

if(!empty($error_msg))echo "<script type='text/javascript'>alert('$error_msg');</script>";


}

}

?>

<div class="notifArea">
<?php

if(!empty($success)){

echo "<div class=\"alert success\">
  <span class=\"closebtn\">&times;</span> <!--X--> 
  <strong>".$success."</strong>
</div>";


}

if(!empty($error_msg)){

echo "<div class=\"alert error\">
  <span class=\"closebtn\">&times;</span> <!--X--> 
  <strong>Nie przesłano pliku. </strong>".$error_msg."
</div>";


}


?>
</div>


 <script src="js/alertutil.js" >

 
</script>  
<div class ="popupLarge" id="download-frame">
<div class="popupLarge-content">
<div class="close" onclick="toggleDownloadDialog()">&times;</div>

    <fieldset>

        <legend>Add a new file to the storage</legend>
        <form method="post" action="files.php" enctype="multipart/form-data">
        <p>
		<label class="uploadFile">
        <input type="file" name="file" />
		<span>Select file</span>
		</label>
		</p>	
		<input type="checkbox" name="public" >Is public upload?</input>
        <p><input type="submit" name="submit" value="Start upload" /></p>
		

        </form>   
    </fieldset>
</div>
</div>

<div class="defContainer" id="myFileList">
<button class="refresh" onclick="fileListUpdate()">Refresh File List</button>
<button class="delete" onclick="deleteWrapper()">Delete</button>
<button class="download" onclick="executeOnSelected('prepare-download', downloadWrapper, false)">Download</button>
<button class="upload" onclick="toggleDownloadDialog()">Upload</button>
<button class="zip" onclick="zipWrapper('<?php echo uniqid('zip') ?>')" id="zip">Zip</button>
<div id="div_path">
<input type="text" name="path" id="path" />
</div>
<div id="nav">
From:<input type="text" name="from" value="1" onkeyup="fileListUpdate()" id="from" /> To: <input type="text" onkeyup="fileListUpdate()" name="lim" value="10" id="lim" /> 
<button class="lor" id="moveLeft" onclick="incLeft()"> &lt </button><button id="moveRight" onclick="incRight()" class="lor"> &gt </button><input type="text" class="smallInput" id="moveSize" value="10" />
</div>
<div id="fileList"></div>

<iframe src="downloader.php" id="downloader" style="display:none"></iframe>

</div>
<script src="js/files.js"></script>
</body>

</html>