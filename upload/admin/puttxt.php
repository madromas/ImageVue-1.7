<?php
//
// r16.2 2006
//

include('../include/secure.inc.php');


$vars = array('ref', 'body');
$varstr = '';
foreach ($vars as $var) {
	if(isset($_POST[$var])) 
	{
		$vvar=$_POST[$var];
		$$var= $vvar;
	}
} 

if(!isset($ref) or !isset($body) ) die('fail');	

$ref=securePath($ref);
if (!file_exists('../'.$ref)) die ('fail');

if(substr($ref,-1)!='/') {
	$path_parts = pathinfo($ref);
	$dir=$path_parts["dirname"];
	$file=$path_parts["basename"]; 
	$dfile='../'.$dir.'/descr.txt';
} 
 else {
	$dir=$ref;
	$file='__dir';
	$dfile='../'.$dir.'descr.txt';
 }


if($lines = @file($dfile)) {
	foreach($lines as $str) {
	   list($key,$var)= explode("\t", $str);
	   $data[$key]=rtrim($var,"\r\n");
	}
}

$data [$file]=urlencode(stripslashes($body));
asort($data);
if($fp=@fopen($dfile,'w')){
	foreach($data as $key=>$val) {
		if($val!='') fputs($fp,$key."\t".$val."\r\n") or die('fail');
	}
	echo ('Save was successfull');
} else echo ("Error: Can't create file");

?>