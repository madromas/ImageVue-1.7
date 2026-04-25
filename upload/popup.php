<?php
/**
 * ImageVue 1.7 - popup.php
 * PHP 8 Compatibility Update
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
$path = $_GET['path'] ?? '';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?= htmlspecialchars($path); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" src="javascript/window.js"></script>
<style type="text/css">
    body { background-color: #FFFFFF; margin: 0px; padding: 10px; }
    .od { position: absolute; left: 0px; top: 0px; width:100%; text-align:right; opacity: .5; filter: alpha(opacity=50); visibility:hidden; }
    .id { float:right; width: 50px; background-color:#FFFFFF; margin: 20px; padding: 10px; font-family:Verdana, sans-serif; font-size: 11px; font-weight: bold; border:double 3px #FFFFFF; }
</style>
</head>
<body onload="resizetoimage();">
    <?php if (!empty($path)): ?>
    <a href="javascript:window.close()" onMouseOver="toggleBox('closelayer',1);" onMouseOut="toggleBox('closelayer',0);">
        <img src="<?= htmlspecialchars($path); ?>" alt="Gallery Image" border="0">
        <div id="closelayer" class="od"><div class="id">close&nbsp;[x]</div></div>
    </a>
    <?php endif; ?>
</body>
</html>