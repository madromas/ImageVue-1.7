<?php
//
// 06.07.2006
//
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
$v="2 r17 06.07.2006";
include('include/version.inc.php');

require_once( 'include/Config.class.php');

$globalpath='';
$config='config.ini';

if (isset($_GET['configfile'])) $config=$_GET['configfile'];
if (!file_exists($config)) die ('Error reading config file');
$Config = new Config( $config,false,false);
$startheading=($Config->get('startheading'));
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
<title><?=$startheading;?></title>
<meta http-equiv="Content-Type" content="text/html">
<script type="text/javascript" src="javascript/swfobject_source.js"></script>

</head>

<body>
<style type="text/css">
html, body {
    height: 100%;
    width: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden; /* Prevents scrollbars */
}

#imagevue {
    width: 100%;
    height: 100%;
}

/* This forces the Ruffle element itself to fill the container */
ruffle-player {
    width: 100%;
    height: 100%;
}
</style>
    <script src="https://unpkg.com/@ruffle-rs/ruffle"></script>

<div id="imagevue"></div>

<script>
    // 1. Global Ruffle configuration must be set BEFORE creating the player
    window.RufflePlayer = window.RufflePlayer || {};
    window.RufflePlayer.config = {
        "autoplay": "on",
        "unmuteOverlay": "hidden",
        "splashScreen": false, // This explicitly disables the Ruffle logo/loading bar
        "letterbox": "off"
    };

    window.addEventListener("load", (event) => {
        const ruffle = window.RufflePlayer.newest();
        const player = ruffle.createPlayer();
        const container = document.getElementById("imagevue");
        container.appendChild(player);

        player.load({
            url: "imagevue.swf",
            base: "./", 
            // 2. We keep flashvars here for the gallery logic
            flashvars: "input=getconfig.php&configfile=config.ini&serverextension=php&ext=php&path=content"
        });
    });
</script>
        
</body>
</html>