<?php
/**
 * ImageVue 1.7 - readfolder.php 
 * PHP 8 Compatibility Bridge for Slideshow
 */
ob_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

$v = '1 r16 17.03.2005';
include("include/version.inc.php");
include("include/secure.inc.php");

// 1. Sanitize Path & Extension
$path = $_GET['path'] ?? 'content';
$ext  = strtolower($_GET['ext'] ?? 'jpg');

// Handle Ruffle 'undefined' errors
if ($path == 'undefined' || empty($path)) {
    $path = 'content'; 
}

// Ensure path is secure and valid - check for both cases
if (function_exists('securePath')) {
    $path = securePath($path);
} elseif (function_exists('securepath')) {
    $path = securepath($path);
}

$extensions = array('jpg', 'jpeg', 'mp3', 'txt', 'png', 'gif');
if ($ext == 'undefined' || !in_array($ext, $extensions)) {
    $ext = 'jpg';
}

// 2. Read Directory
$files = array();
if (is_dir($path)) {
    $dir_handle = opendir($path);
    if ($dir_handle) {
        while (false !== ($file = readdir($dir_handle))) {
            $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            // Fixed the thumbnail check for maximum PHP 8 compatibility
            if ($file != '.' && $file != '..' && $file_ext == $ext && strpos($file, 'tn_') !== 0) {
                $files[] = $file;
            }
        }
        closedir($dir_handle);
    }
}

// 3. Clean Buffer and Output XML
ob_clean();
header('Content-Type: text/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
echo '<index>' . "\n";

if (!empty($files)) {
    natcasesort($files);
    foreach ($files as $file) {
        echo '  <file name="' . htmlspecialchars($file) . '" />' . "\n";
    }
}

echo "</index>";
ob_end_flush();
?>