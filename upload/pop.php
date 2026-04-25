<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
include ("include/version.inc.php");

// PHP 8 FIX: Manually extract variables since import_request_variables is gone
$f_path = $_REQUEST['path'] ?? '';
$f_cached = $_REQUEST['cached'] ?? 0;
$f_desc = $_REQUEST['desc'] ?? 1;
$f_margin = $_REQUEST['margin'] ?? 0;
$f_col1 = $_REQUEST['col1'] ?? '666666';
$f_col2 = $_REQUEST['col2'] ?? 'CCCCCC';
$bgcol = $_REQUEST['bgcol'] ?? 'FFFFFF';
$f_popdisplay = $_REQUEST['popdisplay'] ?? '1,1,1,1,1,1,0';
$coll = $_REQUEST['coll'] ?? '';

if (empty($f_path) || !file_exists('./' . $f_path)) {
    die("File not found.");
}

$imgSize = @getimagesize('./' . $f_path);
$w = $imgSize[0] ?? 0;
$h = $imgSize[1] ?? 0;
$size = round(filesize('./' . $f_path) / 1024);
$date = date('M-d-Y', filectime('./' . $f_path));

$posx = $f_posx ?? ($h / 2);
$posy = $f_posy ?? ($w / 2);

$descr = "";
if ($f_desc) {
    $path_parts = pathinfo('./' . $f_path);
    $dir = $path_parts["dirname"];
    $file = $path_parts["basename"];
    $dfile = $dir . '/descr.txt';

    if (file_exists($dfile)) {
        $lines = file($dfile);
        $data = [];
        foreach ($lines as $str) {
            $parts = explode("\t", trim($str));
            if (count($parts) >= 2) {
                $data[$parts[0]] = $parts[1];
            }
        }
        if (isset($data[$file])) {
            $descr = nl2br(urldecode($data[$file]));
        }
    }
}

$popdisplay = explode(',', $f_popdisplay);
$prepath = 'http://' . str_replace('//', '/', $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/');
$query = urlencode($_SERVER['QUERY_STRING'] ?? '');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?= htmlspecialchars(basename($f_path)) ?></title>
<link rel="stylesheet" href="include/pop.css" type="text/css">
<script type="text/javascript" src="javascript/swfobject_source.js"></script>
<style type="text/css">
    body { padding: 0px; margin: 0px; background-color:#FFFFFF; }
    a:link, a:visited, a:active { color: #<?= $f_col1 ?>; text-decoration: none; }
    a:hover { text-decoration: none; color: #FFFFFF; }
</style>
</head>
<body onload="resizeWinTo('pcontainer');">
<div id="pcontainer" style="margin: <?= (int)$f_margin ?>px">
<?php if (!$f_cached): ?>
    <div id="preload">popup preload module</div>
    <script type="text/javascript">
        var so = new SWFObject("preload.swf", "preload", "<?= $w ?>", "<?= $h ?>", "6", "#<?= $bgcol ?>");
        so.addVariable("path", "<?= addslashes($f_path) ?>");
        <?php if(($popdisplay[0] ?? '') == '1') echo 'so.addVariable("keepflash","true");'; ?>
        so.addVariable("posx", "<?= $posx ?>");
        so.addVariable("posy", "<?= $posy ?>");
        so.addVariable("query", "<?= $query ?>");
        so.addVariable("clickclose", "<?= $popdisplay[5] ?? '0' ?>");								
        so.write("preload");
    </script>
<?php else: ?>
    <?php if(($popdisplay[5] ?? '') == '1'): ?><a href="javascript:window.close();"><?php endif; ?>
    <img src="<?= htmlspecialchars($f_path) ?>" width="<?= $w ?>" height="<?= $h ?>" border="0">
    <?php if(($popdisplay[5] ?? '') == '1'): ?></a><?php endif; ?>
<?php endif; ?>

<?php if (($popdisplay[1] ?? '') == '1'): ?>
    <table width="<?= $w ?>" border="0" cellspacing="0" cellpadding="0">
        <tr><td height="10" bgcolor="#<?= $f_col2 ?>" class="textfgreyarea"><font color="#<?= $f_col1 ?>"><strong> <?= htmlspecialchars(basename($f_path)) ?></strong></font></td></tr>
    </table>
<?php endif; ?>
</div>
</body>
</html>