<?php
ob_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

require_once('include/version.inc.php');
require_once('include/reverse.inc.php');

if (!function_exists('cmp')) {
    function cmp($a, $b) { return strnatcasecmp($a, $b); }
}

// 1. IMPROVED PATH LOGIC
$requested_path = $_GET['path'] ?? 'content';

// If Ruffle sends "true" or a sort code as the path, default back to 'content'
if ($requested_path == 'true' || $requested_path == 'dd' || $requested_path == 'na' || empty($requested_path)) {
    $requested_path = 'content';
}

$requested_path = ltrim($requested_path, '/');

if (is_dir($requested_path)) {
    $path = $requested_path;
} elseif (is_dir("content/" . $requested_path)) {
    $path = "content/" . $requested_path;
} else {
    $path = "content/"; // Final fallback
}

// Ensure trailing slash
$path = rtrim($path, '/') . '/';

ob_clean();
header('Content-Type: text/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?><list>';

if (is_dir($path)) {
    $files = [];
    $subfolders = [];
    $sort = $_GET['sort'] ?? "dd"; // Default to your preferred sort

    $scanned_items = scandir($path);
    foreach ($scanned_items as $file) {
        if ($file == '.' || $file == '..') continue;
        if (is_dir($path . $file)) {
            $subfolders[] = $file;
        } else {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (strpos($file, 'tn_') !== 0 && ($ext == 'jpg' || $ext == 'jpeg')) {
                $files[] = $file;
            }
        }
    }

    // 2. FIXED FOLDER OUTPUT
    if (!empty($subfolders)) {
        usort($subfolders, 'cmp');
        foreach ($subfolders as $folder) {
            // Force the slash between the parent path and subfolder
            $full_path = rtrim($requested_path, '/') . '/' . $folder . '/';
            echo '<folder name="' . htmlspecialchars($folder) . '" path="' . htmlspecialchars($full_path) . '" />';
        }
    }

    // 3. FIXED IMAGE OUTPUT
    if (!empty($files)) {
        usort($files, 'cmp');
        if ($sort == 'nd' || $sort == 'dd') $files = array_reverse($files);

        foreach ($files as $file) {
            $full_image_path = $path . $file;
            $size_info = @getimagesize($full_image_path);
            echo '<image name="' . htmlspecialchars($file) . '" width="' . ($size_info[0] ?? 0) . '" height="' . ($size_info[1] ?? 0) . '" />';
        }
    }
}

echo "</list>";
ob_end_flush();