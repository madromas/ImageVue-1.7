<?php
// PHP 8: Strict error reporting is better for debugging, but keeping your preference:
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

include('include/version.inc.php');
require_once('include/Config.class.php');

$globalpath = '';

// PHP 8 Null Coalescing
$config = $_GET['configfile'] ?? 'slideshowconfig.ini';
if ($config === 'undefined' || empty($config)) {
    $config = 'slideshowconfig.ini'; 
}

// Basic security: prevent directory traversal
$config = basename($config);

if (!file_exists($config)) {
    $config = 'slideshowconfig.ini';
}

$Config = new Config($config, false, false);
$path = (string)($Config->get('path') ?? '');
$startheading = (string)($Config->get('startheading') ?? '');

$mytitle = ($startheading === 'foldername') ? $path : $startheading;

$bgcol = (string)($_GET['bgcol'] ?? $Config->get('bgcol') ?? 'FFFFFF');
$textcol = (string)($Config->get('textcol') ?? '000000');

// Prepare FlashVars for Ruffle
$vars = $Config->listKeys();
$flashVarsArray = [
    "globalpath" => $globalpath,
    "textcol"    => $textcol,
    "configfile" => $config
];

foreach ($vars as $var) {
    $var = trim((string)$var);
    if (isset($_GET[$var])) {
        $flashVarsArray[$var] = (string)$_GET[$var];
    }
}

// Build the query string for the FlashVars
$flashVarsString = http_build_query($flashVarsArray);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars((string)$mytitle) ?></title>
    <style type="text/css">
        body, html { height: 100%; overflow: hidden; background-color: #<?= htmlspecialchars($bgcol) ?>; margin: 0; padding: 0; }
        #container { width: 100%; height: 100%; }
    </style>
    <script src="https://unpkg.com/@ruffle-rs/ruffle"></script>
</head>
<body>

    <div id="container">
        <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="100%" height="100%" id="slideshow">
            <param name="movie" value="slideshow.swf" />
            <param name="flashvars" value="<?= htmlspecialchars($flashVarsString) ?>" />
            <param name="bgcolor" value="#<?= htmlspecialchars($bgcol) ?>" />
            <object type="application/x-shockwave-flash" data="slideshow.swf" width="100%" height="100%">
                <param name="flashvars" value="<?= htmlspecialchars($flashVarsString) ?>" />
                <param name="bgcolor" value="#<?= htmlspecialchars($bgcol) ?>" />
            <p>Flash content failed to load. Please ensure JavaScript is enabled.</p>
            </object>
            </object>
    </div>

</body>
</html>
