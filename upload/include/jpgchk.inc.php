<?php
/**
 * Imagevue prog jpg checker
 * PHP 8 Compatibility Update
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

function jpgchk($file) {
    global $offset, $data, $length;
    
    if (!file_exists($file)) return 0;
    
    $f = @fopen($file, 'rb');
    if (!$f) return 0;
    
    $length = 1024;
    $data = fread($f, $length);
    fclose($f);

    $offset = 0;

    // Skip JPEG header bytes
    getData(11); 
    getData($offset);
    
    $vals = array('pixels', 'dot/inch', 'dot/mm');
    $units = $vals[getData($offset)] ?? 'pixels';

    $xt = getData($offset, 2); 
    $yt = getData($offset, 2);
    
    // Original logic skip
    $skip = (int)$xt * (int)$yt;
    $offset += $skip;

    $stop = false;
    while (!$stop && $offset < $length) {
        if (ord($data[$offset] ?? '') == 255) {
            $m = $offset;
            $subhead = getData($m, 1, 'hex');
            $off = getData($offset, 2);
            
            // C2 marker indicates a Progressive JPEG
            if ($off == 17 && $subhead == 'c2') {
                return 1;
            }

            $offset = $m + $off - 2;
        } else {
            $stop = true;
        }
    }
    return 0;
}

function getData($off, $len = 1, $type = 'dec') {
    global $offset, $data, $length;
    
    $total = ($type == 'dec') ? 0 : '';

    for ($i = 0; $i < $len; $i++) {
        $idx = $off + $i;
        if ($idx >= $length) break;

        $val = ord($data[$idx]);

        if ($type == 'dec') {
            if ($len <= 1 || ($i == ($len - 1))) {
                $total += $val;
            } else {
                // PHP 8 FIX: Use ** for exponentiation, not ^
                $total += $val * (256 ** ($len - $i - 1));
            }
        } elseif ($type == 'hex') {
            $chr = dechex($val);
            $total .= (strlen($chr) == 1) ? '0' . $chr : $chr;
        }
    }
    
    $offset = $off + ($i ?? 0);
    return is_string($total) ? trim($total) : $total;
}
?>