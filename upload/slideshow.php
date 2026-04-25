<?php
//
// 06.07.2006
//
error_reporting(E_ALL ^ E_NOTICE);
$v="2 r17 06.07.2006";
include('include/version.inc.php');

require_once( 'include/Config.class.php');

$globalpath='';
$config='slideshowconfig.ini';

if (isset($_GET['configfile'])) $config=$_GET['configfile'];
if (!file_exists($config)) die ('Error reading config file');
$Config = new Config( $config,false,false);
$path=($Config->get('path'));
$startheading=($Config->get('startheading'));
if($startheading == 'foldername'){
	$mytitle = $path;
} else {
	$mytitle = $startheading;
}
$bgcol=($Config->get('bgcol'));
if (isset($_GET['bgcol'])) $bgcol=$_GET['bgcol'];
if (!isset($bgcol)) $bgcol='FFFFFF';
$textcol=($Config->get('textcol'));

$vars=$Config->listKeys();
$varstr = '';

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?=$mytitle;?></title>
<meta http-equiv="Content-Type" content="text/html">
<script type="text/javascript" src="javascript/swfobject_source.js"></script>
<script type="text/javascript" src="javascript/window.js"></script>
<style type="text/css">
<!--
body, html, div {
	height: 100%;
	overflow: hidden;
}
body {
	background-color: #<?=$bgcol;?>;
	margin: 0px;
	padding: 0px;
}
-->
</style>
</head>

<body>

	<div id="slideshow">
		slideshow module
	</div>
	<script type="text/javascript">
		var so = new SWFObject("slideshow.swf", "slideshow", "100%", "100%", "7", "#<?=$bgcol;?>");
		so.addVariable("globalpath", "<?=$globalpath;?>");
		so.addVariable("textcol", "<?=$textcol;?>");
		so.addVariable("configfile", "<?=$config;?>");
		<?php 
		foreach ($vars as $var) {
			$var=trim($var);
			if(isset($_GET[$var])) {
				$vvar=$_GET[$var];
				echo 'so.addVariable("'. $var . '","' . urlencode($vvar).'");';
			}
		}  
		?>
		so.write("slideshow");
	</script>

</body>
</html>
