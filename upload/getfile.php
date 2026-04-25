<?php
/**
 * ImageVue 2 - PHP 8 Thumbnail Generator Bridge
 */

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
$v = '2 r16 17.03.2005 (PHP 8 Mod)';

require_once("include/version.inc.php");
require_once("include/thumbnail.config.php");

$img = $_GET['img'] ?? '';

// Sanitize input path
if (empty($img) || strpos($img, '..') !== false) {
    die("Invalid image path");
}

$last_slash = strrpos($img, '/');
if ($last_slash !== false) {
    $path = substr($img, 0, $last_slash);
    $file = substr($img, $last_slash + 1);
} else {
    $path = '.';
    $file = $img;
}

$tnpath = $path . '/tn_' . $file;

if (file_exists($tnpath)) {
    header("Expires: " . gmdate('D, d M Y H:i:s', time() + 2000000) . " GMT");
    header("Content-type: image/jpeg");
    readfile($tnpath);
} elseif (!file_exists($img)) {
    die("Image doesn't exist");
} else {
    if (function_exists("imagecreatefromjpeg") && function_exists("imagecreatetruecolor")) {
        $orig_image = @imagecreatefromjpeg($img);
        if (!$orig_image) {
            die("Could not load image");
        }
        
        $orig_x = imagesx($orig_image);
        $orig_y = imagesy($orig_image);
        $thumbstyle = $thumbstyle ?? 'scaletobox';
        
        // Initialize coordinates and dimensions
        $idx = $idy = $isx = $isy = 0;
        $idw = $x = 158;
        $idh = $y = 118;
        $isw = $orig_x;
        $ish = $orig_y;

        if ($thumbstyle == 'scaletobox') {
            if (($orig_x > 158) || ($orig_y > 118)) {
                if (($orig_x / $orig_y) > (4 / 3)) {
                    $y = (int)round($orig_y / ($orig_x / 158));
                    if ($y == 0) $y = 1;
                    $x = 158;
                } else {
                    $x = (int)round($orig_x / ($orig_y / 118));
                    if ($x == 0) $x = 1;
                    $y = 118;
                }
                $idw = $x;
                $idh = $y;
            }
        } else if ($thumbstyle == 'scaleandcrop') {
            if (($orig_x > 158) || ($orig_y > 118)) {
                $x = 158; $y = 118;
                $idw = 158; $idh = 118;
                if (($orig_x / $orig_y) > (158 / 118)) {
                    $ish = $orig_y;
                    $isw = (int)round((158 / 118) * $orig_y);
                    $isx = (int)round(($orig_x - $isw) / 2);
                } else {
                    $isw = $orig_x;
                    $ish = (int)round((118 / 158) * $orig_x);
                    $isy = (int)round(($orig_y - $ish) / 2);
                }
            }
        }

        $sm_image = imagecreatetruecolor($x, $y);
        imagecopyresampled($sm_image, $orig_image, $idx, $idy, $isx, $isy, $idw, $idh, $isw, $ish);
        
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + 2000000) . " GMT");
        header("Content-type: image/jpeg");
        
        // PHP 8 FIX: Second parameter must be NULL, not '', to output to browser
        imagejpeg($sm_image, null, 80);
        
        // Save thumbnail if path is writable
        if (is_writable($path)) {
            imagejpeg($sm_image, $tnpath, ($thumbquality ?? 80));
        }
        
        imagedestroy($sm_image);
        imagedestroy($orig_image);
    } else {
        header("Content-type: image/jpeg");
        readfile('thumb.jpg');
    }
}