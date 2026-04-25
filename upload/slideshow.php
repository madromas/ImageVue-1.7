<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
include('include/version.inc.php');
require_once('include/Config.class.php');

$globalpath = '';

// FIX: If configfile is undefined or missing, force it to 'slideshowconfig.ini'
$config = $_GET['configfile'] ?? 'slideshowconfig.ini';
if ($config == 'undefined' || empty($config)) {
    $config = 'slideshowconfig.ini'; 
}

if (!file_exists($config)) {
    // If the specific ini isn't found, try the default as a last resort
    $config = 'slideshowconfig.ini';
}

$Config = new Config($config, false, false);
$path = $Config->get('path') ?? '';
$startheading = $Config->get('startheading') ?? '';

$mytitle = ($startheading == 'foldername') ? $path : $startheading;

$bgcol = $_GET['bgcol'] ?? $Config->get('bgcol') ?? 'FFFFFF';
$textcol = $Config->get('textcol') ?? '000000';

$vars = $Config->listKeys();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?= htmlspecialchars((string)$mytitle) ?></title>
<meta http-equiv="Content-Type" content="text/html">
<script type="text/javascript" src="javascript/swfobject_source.js"></script>
<script type="text/javascript" src="javascript/window.js"></script>
<style type="text/css">
    body, html { height: 100%; overflow: hidden; }
    body { background-color: #<?= htmlspecialchars((string)$bgcol) ?>; margin: 0px; padding: 0px; }
</style>
</head>
<body>
    <div id="slideshow">slideshow module</div>
    <script type="text/javascript">
        var so = new SWFObject("slideshow.swf", "slideshow", "100%", "100%", "7", "#<?= $bgcol ?>");
        so.addVariable("globalpath", "<?= $globalpath ?>");
        so.addVariable("textcol", "<?= $textcol ?>");
        so.addVariable("configfile", "<?= urlencode($config) ?>");
        <?php 
        foreach ($vars as $var) {
    $var = trim((string)$var);
    if(isset($_GET[$var])) {
        $vvar = (string)$_GET[$var]; // Force string cast for PHP 8
        echo 'so.addVariable("'. $var . '","' . urlencode($vvar).'");';
    }
}
        ?>
        so.write("slideshow");
    </script>
</body>
</html>