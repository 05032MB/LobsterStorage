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
		
		if($query = mysql_query("DELETE FROM files WHERE discName='$param' AND userId='$user_check'")){
		unlink( "up/".$param);
		}
		
		mysql_close($connection);
		

		echo "$param"."$query";
		break;
	}
	case 'get-list-obsolete':
	{
		$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);
	$db = mysql_select_db(Settings::databaseName, $connection);
	
	$query = mysql_query("SELECT name,type,discName,isPublic FROM files WHERE userId='$user_check'");
	
	while($result = mysql_fetch_array($query)) { 
	
	echo '<tr>';
	
		$fn = $result['name']; 
		$ext = $result['type'];
		$av = $result['isPublic'];
		
		//$downLink = '/up/';
		$downLink = $result['discName'];
		
		$toSend = "action=delete&param=";
		$toSend .= $result["discName"];
		
		echo '<td>'.$fn. '</td>'; 
		echo '<td>'.$ext. '</td>'; 
		echo '<td>'.$av. '</td>'; 
		echo '<td>'.$downLink. '</td>'; 
		echo '<td>'."<button class='delete' id='del' onclick=\"ajaxLoad('operator.php', '$toSend' ,ajaxResponseDump)\">Skasuj</button></td>"; 
	
		echo '</tr>';
	}
		mysql_close($connection);
	}

	case 'fetch-files':
	{
		header("Content-type: application/json");
		$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);
		$db = mysql_select_db(Settings::databaseName, $connection);
		$query = mysql_query("SELECT name,type,discName,isPublic FROM files WHERE userId='$user_check'");
		
		$table = array();
		$i = 0;
		while($result = mysql_fetch_array($query))
		{
			$fn = $result['name']; 
			$ext = $result['type'];
			$av = $result['isPublic'];
		
			//$downLink = '/up/';
			$downLink = $result['discName'];
		
			$toSend = "action=prepare-download&param=";
			$toSend .= $result["discName"];
			
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
	case 'prepare-download':
	{
		$connection = mysql_connect(Settings::databaseAddr, Settings::databaseUser, Settings::databasePassword);
		$db = mysql_select_db(Settings::databaseName, $connection);
		$query = mysql_query("SELECT name, discName FROM files WHERE userId='$user_check' AND discName ='$param'");
		
		if($result = mysql_fetch_array($query)){
			$folder = uniqid();
			mkdir("up/".$folder);
			copy("up/".$result['discName'], "up/".$folder."/".$result['name']);
			echo "up/".$folder."/".$result['name'];
		}
		
		mysql_close($connection);
	}

	

}

}else{

echo 'Nie powinno cie tu być. Sio!';
	
}
?>