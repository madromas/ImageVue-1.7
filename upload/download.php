<?
error_reporting(E_ALL^E_NOTICE^E_WARNING);
include('include/secure.inc.php');
$err=0;
if (isset( $_GET['path']))
{
	$path=securePath($_GET['path']);
	if (is_file('./'.$path)) {
		$file_extension=strtolower(substr($path,-3));

		switch( $file_extension )
		{
			
			case "swf": $ctype="application/x-shockwave-flash"; break;
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpeg":
			case "jpg": $ctype="image/jpg"; break;
			default: $err=1;
		}
		if (!$err){
		header("Content-Type: $ctype");
		header("Content-Disposition: attachment; filename=\"".basename($path)."\";" );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($path));
		readfile("$path");
		exit();
		}

	}
} 
?>