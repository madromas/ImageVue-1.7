<?php
/**
 * ImageVue 1.7 - gettxt.php
 * PHP 8 Compatibility Update
 */
ob_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

$v = '2 r16 17.03.2005';
include('include/version.inc.php');

// Standard ImageVue Cache Headers
header("Expires: Mon, 1 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 1. Get and Sanitize Reference
$ref = $_GET['ref'] ?? '';
if (empty($ref)) {
    die("result=error:no_ref");
}

// 2. Determine Pathing
$output_text = "";
if (file_exists($ref)) {
    if (!is_dir($ref)) {
        // It's a specific file
        $path_parts = pathinfo($ref);
        $dir = $path_parts["dirname"];
        $file = $path_parts["basename"];
        $dfile = $dir . '/descr.txt';
    } else {
        // It's a directory
        $dir = rtrim($ref, '/');
        $file = '__dir';
        $dfile = $dir . '/descr.txt';
    }

    // 3. Parse descr.txt
    if (file_exists($dfile) && ($lines = @file($dfile))) {
        $data = [];
        foreach ($lines as $str) {
            // Explode by Tab (Original ImageVue standard)
            $parts = explode("\t", trim($str));
            if (count($parts) >= 2) {
                $data[$parts[0]] = $parts[1];
            }
        }
        
        if (isset($data[$file])) {
            $output_text = $data[$file];
        }
    }
}

// 4. Output the result for Flash
ob_clean();
header("Content-Type: text/plain; charset=utf-8");

// We always start with 'result=' because ImageVue's ActionScript splits the string there
echo 'result=' . $output_text;

ob_end_flush();
?>