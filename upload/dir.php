<?php 
/**
 * ImageVue 2 - PHP 8 Compatibility Bridge
 * Updated for modern directory handling
 */

// 1. Error Handling - Hide notices but show fatal errors
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', 0); // Keep XML output clean

$v = '2 17.03.2005 (PHP 8 Mod)';

// 2. Load dependencies
require_once( 'include/version.inc.php' );
require_once( 'include/reverse.inc.php' );

// Standard comparison function for PHP 8 usort
if (!function_exists('cmp')) {
    function cmp($a, $b) {
        return strnatcasecmp($a, $b);
    }
}

// 3. Path Logic
$contentfolder = $_GET['contentfolder'] ?? 'content';

// Clean the path to prevent traversal
$contentfolder = str_replace(['..', '\\'], ['', '/'], $contentfolder);
$contentfolder = rtrim($contentfolder, '/');

if (!is_dir($contentfolder)) {
    die("Can't open $contentfolder");
}

$sort = $_GET['sort'] ?? "";

// 4. Start Output
header('Content-Type: text/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?><index>';

// 5. Get Group Folders
$groups = [];
$group_items = scandir($contentfolder);

foreach ($group_items as $entry) {
    if ($entry != '.' && $entry != '..' && is_dir($contentfolder . '/' . $entry)) {
        $groups[] = $entry;
    } 
} 

// 6. Sorting Logic
if ($sort == 'na') {
    usort($groups, "cmp");
} elseif ($sort == 'nd') {
    usort($groups, "cmp");
    $groups = array_reverse($groups);
} elseif ($sort == 'rnd') {
    shuffle($groups);
} else {
    $groups = array_reverse($groups);
} 

// 7. Process Groups and Subfolders
foreach ($groups as $entry) {
    $group_path = $contentfolder . '/' . $entry;
    $perms = substr(sprintf('%o', fileperms($group_path)), -3);
    
    echo '<groupfolder name="' . htmlspecialchars($entry) . '" perm="' . $perms . '">';
    
    $folders = [];
    $sub_items = scandir($group_path);

    foreach ($sub_items as $entry2) {
        if ($entry2 != '.' && $entry2 != '..' && is_dir($group_path . '/' . $entry2)) {
            $folders[] = $entry2;    
        }
    }

    // Sort Subfolders
    if ($sort == 'na') {
        usort($folders, "cmp");
    } elseif ($sort == 'nd') {
        usort($folders, "cmp");
        $folders = array_reverse($folders);
    } elseif ($sort == 'rnd') {
        shuffle($folders);
    } else {
        $folders = array_reverse($folders);
    } 

    foreach ($folders as $entry2) {
        $subfolder_path = $group_path . '/' . $entry2;
        $amount = 0;
        
        // Count images in subfolder
        $imgs = scandir($subfolder_path);
        foreach ($imgs as $entry3) {
            $ext = strtolower(pathinfo($entry3, PATHINFO_EXTENSION));
            if (($ext == "jpg" || $ext == "jpeg") && !str_starts_with($entry3, "tn_")) {
                $amount++;
            }
        } 
        
        $folder_perms = substr(sprintf('%o', fileperms($subfolder_path)), -3);
        
        // Build XML line - CRITICAL: Ensure the path is fully qualified to avoid 'undefined'
        echo '<folder path="' . htmlspecialchars($contentfolder . '/' . $entry . '/' . $entry2 . '/') . '" name="' . htmlspecialchars($entry2) . '" amount="' . $amount . '" perm="' . $folder_perms . '"/>';
    } 

    echo '</groupfolder>';
} 

echo '</index>';
?>