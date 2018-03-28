<?php
require_once('session.php');

if(isset($_REQUEST['action']) && isset($_REQUEST['param']))
{
	$action = $_REQUEST['action'];
	$param = $_REQUEST['param'];
	
	switch($action)
	{
		case 'cleanup-download':
		{
		if($_SESSION['ugroup'] == Settings::admin){
			$dir = new DirectoryIterator(Settings::downloadDirectory);
			$unlink_list = array();
			foreach($dir as $item){
			
				if(!$item ->isDot() && $item->isDir())
				{
				echo '<b>Traversing: '.$item->getPathname()."\n<br></b>";
					if(time() - filectime($item ->getPathname()) > $param){
					//echo filectime($item ->getPathname());
						array_push($unlink_list, $item->getPathname());
						//unlink($item->getFilename());
					}
				}
			}
			
			foreach($unlink_list as $iter){
			echo '<b style="color:red;">Unlinking: '.$iter."\n<br></b>";
				chmod($iter,0777);

				
				$diriter = new DirectoryIterator($iter);
				
				foreach($diriter as $rak)	//will not work for directories inside directories but why it should?
				{
					if(!$rak->isDot())unlink($iter."\\".$rak);
				}
				rmdir($iter);
				
			}
			}else{
				echo 'Niewystarczające uprawnienia';
			}
			
		}
	}
}
?>