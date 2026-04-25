<?php
/**
 * ImageVue 1.7 - include/secure.inc.php
 * PHP 8 Compatibility Update
 */

function securePath($path) {
    if (empty($path)) return "";
    
    // Decode the path
    $decoded = urldecode($path);
    
    // PHP 8 Fix: Replaced removed ereg_replace with preg_replace
    // This removes ../ and ./ to prevent directory traversal attacks
    $secured = preg_replace('/\.{2,}\/|\.\//', '', $decoded);
    
    return $secured;
}

function getExt($fname) {
    if (empty($fname)) return "";
    
    // Use pathinfo safely
    $path_parts = pathinfo($fname);
    
    // Ensure the extension exists before calling strtolower
    return isset($path_parts['extension']) ? strtolower($path_parts['extension']) : "";
}

function checkpaths($allowedpath, $path) {
    // PHP 8: Use null coalescing to check if path is set
    if ($path === null || $path === 'undefined' || $path === '') {
        return -1;
    }
    
    if ($allowedpath !== '*') {
        $allowedpath = strtolower((string)$allowedpath);
        $secured_path = strtolower(securePath((string)$path));
        
        // Ensure the path actually starts with the allowed directory
        if (strlen($secured_path) < strlen($allowedpath)) {
            return 0;
        }
        
        if (substr($secured_path, 0, strlen($allowedpath)) !== $allowedpath) {
            return 0;
        }
    }    
    return 1;
}
?>