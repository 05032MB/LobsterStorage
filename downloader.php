<?php
require_once('session.php');

if(isset($_REQUEST['fileLoc']))
{
$file = $_REQUEST['fileLoc'];
	$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);
	$db = mysql_select_db(Settings::databaseName, $connection);
	
	$fileName = substr( $file, strrpos( $file, '/')+1);
	$query = mysql_query("SELECT name, discName FROM files WHERE userId='$user_check' AND name ='$fileName'");
	
	if($result = mysql_fetch_array($query)){
			if (file_exists($file)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.basename($file).'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				ob_clean();
				flush();
				readfile($file);
    //exit;
		}
	}
	
	mysql_close($connection);
}

?>