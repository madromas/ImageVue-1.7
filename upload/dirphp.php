<?php
//
// r16 17.03.2005
//
error_reporting(E_ALL ^ E_NOTICE);
$v='r16 1 17.03.2005';
include ('include/version.inc.php');
$ext=array('php');
$files=array();

 if ($dh = opendir('.')) { 
       while (($file = readdir($dh)) !== false) { 
       if (is_file($file) and (
       in_array(substr(strtolower($file),-3),$ext)))
           $files[]=$file;
        } 
       closedir($dh); 
	  } 
$files=array_reverse($files);	  
foreach ($files as $file)  print "<a href='$file?check=1'>$file</a><br>";       
?>