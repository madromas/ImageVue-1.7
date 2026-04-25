<?
include('../include/secure.inc.php');
include('config.inc.php');
include('passwords.php');                    
echo 'success=';

if (isset($_GET['pass'])) { $pass=strtolower ($_GET['pass']); } else { die ('false'); }
if (isset($_GET['file'])) { $file=securepath ($_GET['file']); } else { die ('false'); }

if (!in_array($pass, array_keys($data))) { echo 'false'; }
else {
	
	
	
	$allowedpath=$data[$pass];
	//if ($path!=substr($file,0,strlen($path))) die ('false');
	$path_parts = pathinfo($file);
	$dirname=$path_parts['dirname'];

	if (!checkpaths($allowedpath,$dirname)) die ('false');
	
	$filename=$path_parts['basename'];
	
	if (!in_array(getExt($filename),$extensions)) die ('false');
	
	if (!file_exists('../'.$dirname)) die ('false');

	if (!file_exists('../'.$file)) die ('false');

	if (!@unlink('../'.$file)) die ('Cannot delete');

	if (file_exists('../'.$dirname.'/tn_'.$filename)) @unlink ('../'.$dirname.'/tn_'.$filename);

	echo "true";
	
	
} 

?>