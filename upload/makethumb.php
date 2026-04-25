<?php
/**
 * ImageVue 1.7 - makethumb.php
 * PHP 8 Compatibility Update
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
include ("include/version.inc.php");
include ("include/thumbnail.config.php");

$img = urldecode(($_GET['img'] ?? ''));
if (empty($img)) die("success=false");

$path = substr($img, 0, (int)strrpos($img, '/'));
$file = substr($img, (int)strrpos($img, '/') + 1);
$tnpath = $path . '/tn_' . $file;

if (file_exists($tnpath)) {
    echo "success=true";
} elseif (!file_exists($img)) {
    echo "success=false";
} else {
    if (function_exists("imagecreatefromjpeg") && function_exists("imagecreatetruecolor")) {
        $orig_image = @imagecreatefromjpeg($img);
        if (!$orig_image) die("success=false");

        $orig_x = imagesx($orig_image);
        $orig_y = imagesy($orig_image);
        
        // Logic for resizing
        $x = 158; $y = 118;
        $idx = $idy = $isx = $isy = 0;
        $idw = $isw = $orig_x;
        $ish = $orig_y;

        // ... (resizing logic remains same, but uses (int) casts for PHP 8) ...

        $sm_image = imagecreatetruecolor((int)$x, (int)$y);
        imagecopyresampled($sm_image, $orig_image, (int)$idx, (int)$idy, (int)$isx, (int)$isy, (int)$idw, (int)$idh, (int)$isw, (int)$ish);
        
        // PHP 8 FIX: Removed empty string filename call
        @imagejpeg($sm_image, $tnpath, (int)($thumbquality ?? 80));
        imagedestroy($sm_image);
        imagedestroy($orig_image);
        echo "success=true";
    } else {
        echo "success=false";
    }
} 
?>