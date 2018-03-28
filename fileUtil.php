<?php

function getExtension($fileName)
{	
	$extension = substr($fileName, strrpos($fileName, '.')+1);
	if(empty($extension))$extension = '?';
	return $extension;
}

function getFileType($fileName)
{
//echo $fileName;
	$extension = getExtension($fileName);
	//echo $extension;
	
    $images = array('jpg', 'gif', 'png', 'bmp', 'jpeg');
    $docs   = array('txt', 'rtf', 'doc');
    $archives   = array('zip', 'rar', '7zip');
	$banned = array('exe');
     
    if(in_array($extension, $images)) return "Images";
    else if(in_array($extension, $docs)) return "Documents";
    else if(in_array($extension, $archives)) return "Archives";
	else if(in_array($extension, $banned)) return "NotAllowed";
    return "Unknown";
}
function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    
    $bytes /= pow(1024, $pow); 
    
    return round($bytes, $precision) . ' ' . $units[$pow]; 
}
function sanitize($data)
{
	$result = htmlspecialchars(stripslashes($data));
	return $result;
}

?>