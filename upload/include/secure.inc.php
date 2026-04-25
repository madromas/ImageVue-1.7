<? 
function securePath($path){
	
	$secured = ereg_replace("\.{2,}/|\./","",urldecode($path));
	return $secured;
}
function getExt($fname) {

	$path_parts = pathinfo($fname);

	return strtolower($path_parts['extension']);

}
function checkpaths($allowedpath, $path) {

if (!isset($path)) return -1;
if ($allowedpath!='*') {
		$allowedpath=strtolower($allowedpath);
		$path=strtolower(securePath($path));
		if (strlen($path)<strlen($allowedpath)) return 0;
		if (substr($path,0,strlen($allowedpath))!=$allowedpath) return 0;
			
	}	
	return 1;
}

?>