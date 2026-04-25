<?php
/**
 * ImageVue 1.7 - download.php 
 * PHP 8 Compatibility Update
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
include('include/secure.inc.php');

$err = 0;

if (isset($_GET['path'])) {
    // 1. Secure the path using our updated securePath function
    $path = securePath((string)$_GET['path']);
    
    // 2. Check if file exists safely
    if (!empty($path) && is_file('./' . $path)) {
        // Use pathinfo for better extension detection
        $file_extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        switch ($file_extension) {
            case "swf":  $ctype = "application/x-shockwave-flash"; break;
            case "gif":  $ctype = "image/gif"; break;
            case "png":  $ctype = "image/png"; break;
            case "jpeg":
            case "jpg":  $ctype = "image/jpeg"; break;
            default:     $err = 1;
        }

        if (!$err) {
            // 3. Modernized headers for binary transfer
            header("Content-Type: $ctype");
            header("Content-Disposition: attachment; filename=\"" . basename($path) . "\";");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . filesize($path));
            
            // Clear output buffer to prevent file corruption
            if (ob_get_length()) ob_clean();
            flush();
            
            readfile($path);
            exit();
        }
    }
} 
?>