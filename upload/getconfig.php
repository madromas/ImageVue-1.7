<?php
/**
 * ImageVue 1.7 - PHP 8 Compatibility Bridge
 * Updated for Ruffle/Flash LoadVars support
 */

// 1. Enable error reporting to catch the 500 error cause
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Start buffering to prevent accidental whitespace/warnings from ruining the Flash output
ob_start();

// 3. Include the Config class
// Using __DIR__ ensures it finds the file regardless of server config
require_once(__DIR__ . '/include/Config.class.php');

// 4. Capture and sanitize inputs
$file    = $_GET['configfile'] ?? 'config.ini';
$section = $_GET['section']    ?? null;
$type    = $_GET['type']       ?? 'TEXT';

// Logic: Your lang.ini uses [LANGUAGE], but Ruffle requests 'language'.
// We force uppercase to ensure they match.
if ($section) {
    $section = strtoupper(trim($section));
}

// 5. Initialize the Config class
// Ensure the file exists before processing
if (!file_exists($file)) {
    ob_clean();
    header("Content-Type: text/plain");
    die("&error=File " . htmlspecialchars($file) . " not found&loaded=false&EOF=1&");
}

try {
    $Config = new Config($file, false, true);
    
    // toString arguments: format, section, urlencode
    $output = $Config->toString($type, $section, true);

    // 6. Final Clean and Output
    // We clear the buffer in case Config.class.php triggered any "Deprecated" notices
    ob_clean();

    // Set headers for Flash
    header("Content-Type: text/plain; charset=utf-8");
    header("Cache-Control: no-cache, must-revalidate");

    // Format the string specifically for ImageVue's ActionScript expectations
    // Leading & is crucial for Flash LoadVars
    echo "&" . trim($output, "& ") . "&loaded=true&EOF=1&";

} catch (Throwable $e) {
    ob_clean();
    header("Content-Type: text/plain");
    echo "&error=PHP_Error&msg=" . urlencode($e->getMessage()) . "&loaded=false&";
}

exit;