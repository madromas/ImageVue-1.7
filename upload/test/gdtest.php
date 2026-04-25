<?

import_request_variables('gp','f_');

if(!$f_quiet) {
include('header.inc.php');
ob_start();
	phpinfo(8);
	$phpinfo=ob_get_contents();

$gd = strstr($phpinfo, '<h2 align="center"><a name="module_gd">');

$pos = strpos($gd, "</table>");

$gd = substr($gd,0,$pos);
ob_end_clean();
$gd=str_replace ( '</td><td align="left">', ' ', $gd);
echo '<b>GD2</b> Probe:<br><br>';
echo 'If everything is okay you should see a green mark below,<br>otherwise contact your hosting provider.<br><br>';
echo 'PHPInfo: ',nl2br(strip_tags($gd));
echo '<br>';
echo 'ImageCreateFromJpeg...';
$orig = imagecreatefromjpeg('test.jpg');
echo "<span class='green'>OK</span><br>";
echo 'ImageCreateTrueColor...';
$new = imagecreatetruecolor(20, 20);
echo "<span class='green'>OK</span><br>";
echo 'ImageCopyResampled...';
Imagecopyresampled($new, $orig, 0, 0, 0, 0, 20, 20, 40, 40);
echo "<span class='green'>OK</span><br><br></font>";
echo '<b>Verdict:</b></span> <img src=gdtest.php?quiet=1 align=absmiddle>';
imagedestroy ($orig);
imagedestroy ($new);
}
else
{
$orig = imagecreatefromjpeg('test.jpg');
$new = imagecreatetruecolor(20, 20);
Imagecopyresampled($new, $orig, 0, 0, 0, 0, 20, 20, 40, 40);
Header("Content-type: image/jpeg");
imageJPEG($new, '', 100);
imagedestroy ($orig);
imagedestroy ($new);
}
?>