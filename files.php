<!DOCTYPE html>

<html>

<head>
<meta charset="UTF-8" />
<title>FILES</title>
<link rel="stylesheet" type="text/css" href="main.css" />
<!--<link rel="stylesheet" type="text/css" href="main.css" />-->
<link rel="stylesheet" type="text/css" href="alerts.css">
</head>
<body onload="ajaxLoad('operator.php', 'action=fetch-files&param=NULL', buildFileTable)">
<?php
require('session.php');
include('fileUtil.php');
?>
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

<?php


$error_msg = '';
$success = "";

$size_limit = 500000;


if(isset($_FILES['file']))
{
$target_path = "up/";
$discName = rand() . time();
$target_file = $target_path . $discName;

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
        $success = "The file ".$target_file. " has been uploaded.";
		
		$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);
		$db = mysql_select_db(Settings::databaseName, $connection);
		
		$user = $_SESSION['login_user'];
		$type = getExtension(basename($_FILES['file']['name']));
		$filenam = mysql_real_escape_string(sanitize(basename( $_FILES['file']['name'])));
		
		$sqlcom = mysql_query("INSERT INTO files(fileId, userId, name, discName, type) VALUES (NULL, '$user','$filenam','$discName','$type')", $connection);
		
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
  <strong>Przesłano plik. </strong>".$target_file."
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


 <script src="alertutil.js" >

 
</script>  <!--<img src="icons8-folder-26.png" id="imageid">XSS cheat sheet dont try it at home-->
<!--<script> alert(1); </script>-->
<!--<img src='1' onerror=document.getElementById('imageid').src='http://www.wykop.pl/cdn/c3201142/comment_vUbTk3pGxQIidFy5em55HinkGRW0rGt8.jpg'>-->
<!--<img src='1' onerror="document.body.style.backgroundImage=url('http:\\www.wykop.pl\cdn\c3201142\comment_vUbTk3pGxQIidFy5em55HinkGRW0rGt8.jpg')">-->
 
<!--<img src='1' onerror='alert(String.fromCharCode(65, 78, 84,79,78,73,32,68,65,76,32,83,73,69,32,90,72,65,67,75,79,87,65,67,32,80,90,68,82,32,72,65,67 ,75, 69, 82, 32, 66, 79, 78, 90, 79, 32, 50, 48, 49, 56, 65, 68))'>;-->

<!--<div class="defContainer">


    <fieldset>
        <legend>Add a new file to the storage</legend>
        <form method="post" action="files.php" enctype="multipart/form-data">
        <!--<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
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
</div>-->

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
<button class="refresh" onclick="ajaxLoad('operator.php', 'action=fetch-files&param=NULL', buildFileTable)">Refresh File List</button>
<button class="delete" onclick="executeOnSelected('delete', ajaxResponseDump)">Delete</button>
<button class="download" onclick="executeOnSelected('prepare-download', downloadWrapper, false)">Download</button>
<button class="upload" onclick="toggleDownloadDialog()">Upload</button>
<div id="fileList"></div>
<?php
	

?>

<iframe src="downloader.php" id="downloader" style="display:none"></iframe>

</div>
<script>
function toggleDownloadDialog()
{
	var downloadFrame = document.getElementById('download-frame');
	//console.log(downloadFrame.style.display);
	if(downloadFrame.style.display == 'none' || downloadFrame.style.display == '' || downloadFrame.style.display == 'undefined' )downloadFrame.style.display = 'block';
	else downloadFrame.style.display = 'none';
}
function executeOnSelected(action, callback, reloadNeeded)
{
	if(reloadNeeded === undefined)reloadNeeded = true;
	var checkedValue = null; 
	var inputElements = document.getElementsByClassName('checkBoxFileList');
	for(var i=0; inputElements[i]; ++i){
      if(inputElements[i].checked){
          checkedValue = inputElements[i].id;
		  ajaxLoad('operator.php', "action="+action+"&param="+checkedValue, callback);
      }
	}
	if(reloadNeeded)ajaxLoad('operator.php', 'action=fetch-files&param=NULL', buildFileTable);
}

function downloadWrapper(xhttp)
{	
	//console.log(xhttp.responseText);
	var warframe = document.getElementById('downloader');
	warframe.src ="downloader.php?fileLoc="+xhttp.responseText;

}

/*function downloadFire(xhttp)
{
	console.log(xhttp);
	
	var saveByteArray = (function () {
    var a = document.createElement("a");
    document.body.appendChild(a);
    a.style = "display: none";
    return function (data, name) {
        var blob = new Blob(data, {type: "octet/stream"}),
            url = window.URL.createObjectURL(blob);
        a.href = url;
        a.download = name;
        //a.click();
        window.URL.revokeObjectURL(url);
    };
}());
	saveByteArray([xhttp.reponseText], "download.txt");
	
}*/

function buildFileTable(data)
{	//data.res
	//console.log(data);
	data  = data.responseText;
	//console.log(JSON.stringify(data));
	data = JSON.parse(data);
	var Table="<table id=\"fileList\" class=\"fileList\"><tr><th></th><th>Nazwa pliku</th><th>Rozszerzenie pliku</th><th>Udostępniony publicznie?</th><th>Link do pobrania</th><th></th></tr>";
	for(var i=0; i<data.length; i++)
	{
		Table += "<tr>";
		Table += "<td><input type=\"checkbox\" class=\"checkBoxFileList\" name=\""+data[i]["link"]+"\"id=\""+data[i]["link"]+"\" ></td>";
		Table +="<td>"+data[i]["name"]+"</td>";
		Table +="<td>"+data[i]["ext"]+"</td>";
		Table +="<td>"+data[i]["isPublic"]+"</td>";
		Table +="<td>"+data[i]["link"]+"</td>";
		/*Table +="<td>"+data[i]["actionButton1"]+"</td>";*/
		Table += "</tr>";
	}
	Table +="</table>";
	document.getElementById("fileList").innerHTML = Table;
}
function ajaxResponseDump(xhttp)
{
	alert(xhttp.responseText);
}
function ajaxLoad(file, data ,callback)
{
	if(window.XMLHttpRequest){
			xhttp = new XMLHttpRequest();
	}else{
			xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			callback(this);
		}
	}
	
	xhttp.open("POST", file, true);
	xhttp.setRequestHeader("Content-type", 'application/x-www-form-urlencoded');
	xhttp.send(data);

}
</script>
</body>

</html>