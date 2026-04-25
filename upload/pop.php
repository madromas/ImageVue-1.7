<?
//
// r16 17.03.2005
//
error_reporting(E_ALL ^ E_NOTICE);
$v="1 r16 17.03.2005";
include ("include/version.inc.php");
$f_cached=0;
$f_desc=1;
$f_margin=0;
$f_col1='666666';
$f_col2='CCCCCC';
$bgcol='FFFFFF';
$f_popdisplay='1,1,1,1,1,1,0';
import_request_variables ('gp','f_');
list($w, $h, $t) = getImageSize($f_path);
$size=filesize('./'.$f_path);
$date=date('M-d-Y', filectime('./'.$f_path));
if ($f_posx and $f_posy) {
$posx=$f_posx;
$posy=$f_posy;
} else {
$posx=$h/2;
$posy=$w/2;
}

if(file_exists('./'.$f_path) and $f_desc) {
$path_parts = pathinfo('./'.$f_path);
$dir=$path_parts["dirname"];
$file=$path_parts["basename"];
$descr="";
$dfile=$dir.'/descr.txt';
/*
[imageinswf][heading][imageattributes][description][globalurl]
*/

$popdisplay = explode (',',$f_popdisplay);


if($lines = @file($dfile)) {

	foreach($lines as $str) {
		list($key,$var)= explode("\t", $str);
		$data[$key]=$var;
		}
		if(in_array($file,array_keys($data))) $descr=nl2br(urldecode($data[$file]));//$t->assign ('descr',nl2br(urldecode($data[$file])));
	}
}


$twidth=100;$w<400?400:$w; //:$w-205-$f_margin*2;
$size=round($size/1024);
$prepath=stripslashes($_SERVER["HTTP_HOST"]).stripslashes(dirname($_SERVER["PHP_SELF"])).'/'; //
$prepath='http://'.str_replace  ('//','/',str_replace ('\\','/',$prepath));
$file=basename($f_path);
$query=urlencode($_SERVER['QUERY_STRING']);
/*echo "<hr>";
echo "prepath: $prepath<br> file: $file<br>query: $query<br>".urldecode ($query);
echo "<hr>";*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?=$file?></title>
<meta http-equiv="Content-Type" content="text/html">
<link rel="stylesheet" href="include/pop.css" type="text/css">
<script type="text/javascript" src="javascript/swfobject_source.js"></script>
<script type="text/javascript">
<!--
function getRefToDivMod( divID, oDoc ) {
  if( !oDoc ) { oDoc = document; }
  if( document.layers ) {
    if( oDoc.layers[divID] ) { return oDoc.layers[divID]; } else {
      for( var x = 0, y; !y && x < oDoc.layers.length; x++ ) {
        y = getRefToDivMod(divID,oDoc.layers[x].document); }
      return y; } }
  if( document.getElementById ) { return oDoc.getElementById(divID); }
  if( document.all ) { return oDoc.all[divID]; }
  return document[divID];
}
function resizeWinTo( idOfDiv ) {
  var oH = getRefToDivMod( idOfDiv ); if( !oH ) { return false; }
  var oW = oH.clip ? oH.clip.width : oH.offsetWidth;
  var oH = oH.clip ? oH.clip.height : oH.offsetHeight; if( !oH ) { return false; }
  var x = window; x.resizeTo( oW + 200, oH + 200 );
  var myW = 0, myH = 0, d = x.document.documentElement, b = x.document.body;
  if( x.innerWidth ) { myW = x.innerWidth; myH = x.innerHeight; }
  else if( d && d.clientWidth ) { myW = d.clientWidth; myH = d.clientHeight; }
  else if( b && b.clientWidth ) { myW = b.clientWidth; myH = b.clientHeight; }
  if( window.opera && !document.childNodes ) { myW += 16; }
  	var myw = oW + ( ( oW + 200 ) - myW )+(<?=$f_margin*2?>);
	var myh = oH + ( (oH + 200 ) - myH )+(<?=$f_margin?>*2);
	if(myw > screen.availWidth){
		myw = screen.availWidth;
	}
	if(myh > screen.availHeight){
		myh = screen.availHeight;
	} 
  x.resizeTo( myw, myh );
  var scW = screen.availWidth ? screen.availWidth : screen.width;
  var scH = screen.availHeight ? screen.availHeight : screen.height;
  x.moveTo(Math.round((scW-myw)/2),Math.round((scH-myh)/2));
}
// -->
</script>

<style type="text/css">
<!--
body {
	padding: 0px;
	margin: 0px;
	background-color:#FFFFFF;
}
a:visited {
	color: #<?=$f_col1?>;
	text-decoration: none;
}
a:link {
	color: #<?=$f_col1?>;
	text-decoration: none;
}
a:active {
	color: #<?=$f_col1?>;
	text-decoration: none;
}
a:hover {
	text-decoration: none;
	color: #FFFFFF;
}
-->
</style>
</head>

<body onload="resizeWinTo('pcontainer');">

<div id="pcontainer" style="margin: <?=$f_margin?>px">
<? 

if (!$f_cached)
{
		?>
		<div id="preload">
		popup preload module
		</div>
		<script type="text/javascript">
		var so = new SWFObject("preload.swf", "preload", "<?=$w?>", "<?=$h?>", "6", "#<?=$bgcol;?>");
		
		so.addVariable("path", "<?=$f_path;?>");
		<?
		if($popdisplay[0]=='1') echo 'so.addVariable("keepflash","true");';
		if ($posx and $posy)  { echo 'so.addVariable("posx","'.$posx.'");';
								echo 'so.addVariable("posy","'.$posy.'");';
		}?>
		so.addVariable("query", "<?=$query;?>");
		so.addVariable("clickclose", "<?=$popdisplay[5];?>");								
		so.addVariable("coll", "<?=$coll;?>");
		so.write("preload");
	</script>
	<?
} else
{
	if($popdisplay[5]=='1') {
		?><a href="javascript:window.close();"><?
	}
	?><img src="<?=$f_path?>" width="<?=$w?>" height="<?=$h?>" border="0"><?
	if($popdisplay[5]=='1') {
		?></a><?
	}
}
	//[imageinswf][heading][imageattributes][description][globalurl]


if ($popdisplay[1]=='1') {
?><table width="<?=$w?>" border="0" cellspacing="0" cellpadding="0" >

		<tr><td height="10" bgcolor="#<?=$f_col2?>" class="textfgreyarea"><font color="#<?=$f_col1?>"><strong> <?=$file?></strong></font></td>
		</tr></table><? }

		if ($popdisplay[2]=='1' or $popdisplay[3]=='1') {
		?><table border="0" width="100%" cellspacing="0" cellpadding="0" >
		<tr>
		<?
		if ($popdisplay[2]=='1') {
			?>
		<td width="150" valign="top" class="textfattribarea"><font color="#<?=$f_col1?>"><strong>Dimensions:</strong> <?=$w?>
			x <?=$h?><br> <strong>Size:</strong> <?=$size?>Kb<br> <strong> Date:</strong> <?=$date?></font></td>
			<? }
			if ($popdisplay[3]=='1'){ ?>
				<td  valign="top" class="textf"><div align="left"><font color="#<?=$f_col1?>"><?=$descr?></font></div></td> <? } ?>
		</tr>
		</table><? }

			if ($popdisplay[4]=='1') {
			?><table width="<?=$w?>" border="0" cellspacing="0" cellpadding="0" >
		<tr>
		<td bgcolor="#<?=$f_col2?>" class="textfgreyarea"><a href="<?=$prepath?><?=$f_path?>" target="_blank"><?=$prepath?><?=$f_path?></a></td>
		</tr></table><? }

?></div></body></html>