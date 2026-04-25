<?php
//
// r16.2 2006
//
error_reporting(E_ALL ^ E_NOTICE);
$v='1 r16 17.03.2005';
include ("include/version.inc.php");
include ("include/secure.inc.php");
$path = securepath($_GET['path']);
$ext = strtolower($_GET['ext']);
$extensions=array ('jpg','mp3','txt');
if (!in_array($ext,$extensions)) die();
if (!$path) $path='mp3';
$dir_handle = opendir($path) or die("Unable to open $path");
echo '<?xml version="1.0" ?>
<index>
'; 
$files=array();
while ($file = readdir($dir_handle)) 
{
 	if ($file != '.' and $file != '..' and strtolower(substr ($file, -3, 3)) == $ext) $files[]=$file;
}

if(count($files)) {
	sort ($files);
	foreach ($files as $file) {
		
		echo '<file name="', $file,'" />
';
	}
}
closedir($dir_handle);
echo "</index>";
?>