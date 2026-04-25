<?php 
/**
 * ImageVue - PHP 8 File Lister
 */

// Hide notices, show errors
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
$v = 'r16 1 17.03.2005 (PHP 8 Mod)';

require_once('include/version.inc.php');

$ext = ['php'];
$files = [];

// Use scandir for a cleaner, modern PHP 8 approach
if (is_dir('.')) {
    $items = scandir('.');
    foreach ($items as $file) {
        // Skip directories and hidden files
        if (is_file($file)) {
            // Get file extension reliably
            $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($file_ext, $ext)) {
                $files[] = $file;
            }
        }
    }
}

// Reverse the list
$files = array_reverse($files);

// Output links
foreach ($files as $file) {
    // htmlspecialchars protects against weird filenames breaking the HTML
    $safe_file = htmlspecialchars($file);
    echo "<a href='$safe_file?check=1'>$safe_file</a><br>\n";
}
?>