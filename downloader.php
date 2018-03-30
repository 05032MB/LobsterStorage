<?php
require_once('session.php');

if(isset($_REQUEST['fileLoc']))
{
	$arr = explode("|",$_REQUEST['fileLoc']);
	
	$loc = $arr[0];
	$dname = $arr[1];
	//echo $loc." ".$dname;
	//$file = $_REQUEST['fileLoc'];
	
	$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);
	$db = mysql_select_db(Settings::databaseName, $connection);
	
	$fileName = substr( $loc, strrpos( $loc, '/')+1);
	
	$query = mysql_query("SELECT name, discName, userId, discType FROM files WHERE userId='$userId' AND name ='$fileName' AND discName='$dname'"); //b³¹d!
	if($result = mysql_fetch_array($query)){
	
			if (file_exists($loc)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.basename($loc).'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($loc));
				ob_clean();
				flush();
				readfile($loc);
    //exit;
		}
		if($result['discType']=='ZIP_TEMPORARY'){
			mysql_query("DELETE FROM files WHERE name='$fileName' AND userId='$userId' AND discName='$dname'");
		}
	}
	
	mysql_close($connection);
}

?>