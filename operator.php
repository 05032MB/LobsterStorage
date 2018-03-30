<?php
require_once('session.php');

//$login_session

if(isset($_POST['action']) && isset($_POST['param'])){

$action = $_POST['action'];
$param = $_POST['param'];
$query;

switch($action)
{
	case 'delete':
	{
		
		$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);
		$db = mysql_select_db(Settings::databaseName, $connection);
		
		if($query = mysql_query("DELETE FROM files WHERE discName='$param' AND userId='$userId'")){
		unlink( Settings::uploadDirectory.$param);
		}
		
		mysql_close($connection);
		

		echo "$param"."$query";
		break;
	}

	case 'fetch-files':
	{
		header("Content-type: application/json");
		
		$split = explode("|", $param);
		$offset = intval($split[0]);
		$limit = intval($split[1]) - $offset+1;
		$offset--;
		
		//echo $offset.$limit;
		
		$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);
		$db = mysql_select_db(Settings::databaseName, $connection);
		if($limit>0 && $offset>=0)$query = mysql_query("SELECT name,type,discName,isPublic FROM files WHERE userId='$userId' LIMIT $limit OFFSET $offset");
		else $query = mysql_query("SELECT name,type,discName,isPublic FROM files WHERE userId='$userId'");
		
		$table = array();
		$i = 0;
		while($result = mysql_fetch_array($query))
		{
			$fn = $result['name']; 
			$ext = $result['type'];
			$av = $result['isPublic'];
		
			//$downLink = '/up/';
			$downLink = $result['discName'];
		
			//$toSend = "action=prepare-download&param=";
			//$toSend .= $result["discName"];
			
			$table[$i] = array();
			$table[$i]["name"] = $fn;
			$table[$i]["ext"] = $ext;
			$table[$i]["isPublic"] = $av;
			$table[$i]["link"] = $downLink;
			//$table[$i]["actionButton1"] = "<button class='download' id='down' onclick=\"ajaxLoad('operator.php', '$toSend' ,downloadFire)\">Skasuj</button></td>";
			
			$i++;
			
		}
		mysql_close($connection);
		
		$table = json_encode($table);
		
		//if($result == false)*/echo json_last_error();
		echo $table;
		
		break;
	}
	case 'pack-files-zip':
	{
		$split = explode("|", $param);
		$file = $split[0];
		$archive = $split[1];
		
		$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);
		$db = mysql_select_db(Settings::databaseName, $connection);
		$query = mysql_query("SELECT name, discName FROM files WHERE userId='$userId' AND discName ='$file'");
		
		if($result = mysql_fetch_array($query)){

			//echo Settings::downloadDirectory.$archive;
			@mkdir(Settings::downloadDirectory.$archive);
		
			$zip = new ZipArchive();

			if ($zip->open(Settings::downloadDirectory.$archive."\\".$archive.".zip", ZipArchive::CREATE)===TRUE)
			{
				$zip->addFile(Settings::uploadDirectory.$result['discName'], $result['name']);
				
				$query = mysql_query("SELECT name, discName FROM files WHERE userId='$userId' AND discName ='$archive'");
				if(mysql_num_rows($query) == 0)
				{
				//echo '[[';
					$query = mysql_query("INSERT INTO files(discName, discType, userId, name) VALUES ('$archive', 'ZIP_TEMPORARY', '$userId', '$archive.zip')"); //create temporary resource
				}
				
				$zip->close();
			}
		}
		
		mysql_close($connection);
	}
	case 'prepare-download':
	{
		$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);
		$db = mysql_select_db(Settings::databaseName, $connection);
		$query = mysql_query("SELECT name, discName FROM files WHERE userId='$userId' AND discName ='$param'");
		
		if($result = mysql_fetch_array($query)){
			$folder = uniqid();
			mkdir(Settings::downloadDirectory.$folder);
			copy(Settings::uploadDirectory.$result['discName'], Settings::downloadDirectory.$folder."/".$result['name']);
			echo Settings::downloadDirectory.$folder."/".$result['name'];
		}
		
		mysql_close($connection);
	}

	

}

}else{

echo 'Nie powinno cie tu być. Sio!';
	
}
?>