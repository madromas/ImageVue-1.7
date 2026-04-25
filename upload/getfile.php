<?php

error_reporting(E_ALL ^ E_NOTICE);
$v='2 r16 17.03.2005';
include ("include/version.inc.php");
include ("include/thumbnail.config.php");
$img=$_GET['img'];
$path = substr($img, 0, strrpos($img, '/'));
$file = substr($img, strrpos($img, '/') + 1);
$tnpath = $path . '/tn_' . $file;

if (@file_exists($tnpath)) 
{
    header("Expires: ".date ('d M Y',time()+2000000));
    Header("Content-type: image/jpeg");
    readfile($tnpath);
} elseif (!file_exists($img)) 
{
    die("Image doesn't exist");
} else 
{
	if (function_exists("imagecreatefromjpeg") and function_exists("imagecreatetruecolor")) 
	{
		$orig_image = imagecreatefromjpeg($img);
		$orig_x = imagesx($orig_image);
		$orig_y = imagesy($orig_image);
	if($thumbstyle=='scaletobox') {
	if (($orig_x > 158) || ($orig_y > 118)){
		$idx=0;
		$idy=0;
		$isx=0;
		$isy=0;
		$isw=$orig_x;
		$ish=$orig_y;
        if (($orig_x / $orig_y) > (4 / 3)) {
            $y = round($orig_y / ($orig_x / 158));
		if($y==0){
			$y=1;
		}
            $x = 158;
        } else {
            $x = round($orig_x / ($orig_y / 118));
		if($x==0){
			$x=1;
		}
            $y = 118;
        }
		$idw=$x;
		$idh=$y;
	}
	} else if($thumbstyle=='scaleandcrop') {
	if (($orig_x > 158) || ($orig_y > 118)){
		$x=158;
		$y=118;
		$idx=0;
		$idy=0;
		$idh=118;
		$idw=158;
        if (($orig_x / $orig_y) > (158 / 118)) {
		$isy=0;
		$ish=$orig_y;
		$isx=round(($orig_x-((158/118)*$orig_y))/2);
		$isw=round((158/118)*$orig_y);
        } else {
		$isx=0;
		$isw=$orig_x;
		$isy=round(($orig_y-((118/158)*$orig_x))/2);
		$ish=round((118/158)*$orig_x);
        }}
	} else {
		$x=158;
		$y=118;
		$idx=0;
		$isx=0;
		$idw=158;
		$isw=$orig_x;
		$idy=0;
		$isy=0;
		$idh=118;
		$ish=$orig_y;
	}
        $sm_image = imagecreatetruecolor($x, $y);
        Imagecopyresampled($sm_image, $orig_image, $idx, $idy, $isx, $isy, $idw, $idh, $isw, $ish);
		header("Expires: ".date ('d M Y',time()+2000000));
        Header("Content-type: image/jpeg");
        imageJPEG($sm_image, '', 80);
		@imageJPEG($sm_image, $tnpath, $thumbquality);
        imagedestroy ($sm_image);
        imagedestroy ($orig_image);
    } else 
	{
		header("Expires: ".date ('d M Y',time()+2000000));
        header("Content-type: image/jpeg");
        readfile ('thumb.jpg');

    } 
} 
?>