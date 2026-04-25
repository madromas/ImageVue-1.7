<?php
//
// 06.07.2006
//
error_reporting(E_ALL ^ E_NOTICE);
$v="2 r17 29.07.2006";
include('../include/version.inc.php');
require_once('../include/Config.class.php');
require ('../include/det.inc.php');

$globalpath='';
$config='adminconfig.ini';

if (isset($_GET['configfile'])) $config=$_GET['configfile'];
if (!file_exists($config)) die ('Error reading config file');
$Config = new Config( $config,false,false);
$startheading=($Config->get('mainheadtext'));
$bgcol=($Config->get('bgcol'));
if (isset($_GET['bgcol'])) $bgcol=$_GET['bgcol'];
if (!isset($bgcol)) $bgcol='FFFFFF';

$ibrowser = getBrowser();
$iplatform = getPlatform();

$vars=$Config->listKeys();
$varstr = '';


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?=$startheading;?></title>
<meta http-equiv="Content-Type" content="text/html">
<script type="text/javascript" src="../javascript/swfobject_source.js"></script>
<script type="text/javascript" src="../javascript/window.js"></script>
<script type="text/javascript" src="upload.js"></script>
<script LANGUAGE="JavaScript">
function ClipBoard(tocopy)
{
holdtext.innerText = tocopy;
Copied = holdtext.createTextRange();
Copied.execCommand("Copy");
}
</SCRIPT>
<TEXTAREA ID="holdtext" STYLE="display:none;">
</TEXTAREA>
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

<body onLoad="setflash('movie', 'js', 'true')">

	<div id="admin">
		admin module
	</div>
	<script type="text/javascript">
		var so = new SWFObject("admin.swf", "movie", "100%", "100%", "7", "#<?=$bgcol;?>");
		so.addVariable("globalpath", "<?=$globalpath;?>");
		so.addVariable("configfile", "<?=$config;?>");
		so.addVariable("browser", "<?=$ibrowser;?>");
		so.addVariable("platform", "<?=$iplatform;?>");
		<?php 
		foreach ($vars as $var) {
			$var=trim($var);
			if(isset($_GET[$var])) {
				$vvar=$_GET[$var];
				echo 'so.addVariable("'. $var . '","' . urlencode($vvar).'");';
			}
		}  
		?>
		so.write("admin");
	</script>
	
	<IFRAME name="form_frame" src="upload.php" width="0" height="0"></IFRAME>
</body>
</html>
