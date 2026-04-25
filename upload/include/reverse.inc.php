<?php
/**
 * ImageVue 1.7 - include/reverse.inc.php
 * PHP 8 Compatibility Update
 */

function reverse($array) {
    if (!is_array($array)) {
        return array();
    }
    // PHP 8: Use the built-in array_reverse for better performance and reliability
    return array_reverse($array);
}

if (!function_exists('cmp')) {
    function cmp($a, $b) {
        // PHP 8: Use strnatcasecmp for "natural" sorting (e.g., image2 before image10)
        // This is much more robust than manual < or > comparisons
        return strnatcasecmp((string)$a, (string)$b);
    }
}
?>