<?
error_reporting(E_ALL ^ E_NOTICE);
if (isset($_GET['path'])) $path=$_GET['path'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?=$path;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" src="javascript/window.js"></script>
<script type="text/javascript">
<!--
function toggleBox(szDivID, iState) // 1 visible, 0 hidden
{
    if(document.layers)	   //NN4+
    {
       document.layers[szDivID].visibility = iState ? "show" : "hide";
    }
    else if(document.getElementById)	  //gecko(NN6) + IE 5+
    {
        var obj = document.getElementById(szDivID);
        obj.style.visibility = iState ? "visible" : "hidden";
    }
    else if(document.all)	// IE 4
    {
        document.all[szDivID].style.visibility = iState ? "visible" : "hidden";
    }
}
// -->
</script>
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
	margin: 0px;
	padding: 10px;
}
.od {
	position: absolute;
	left: 0px;
	top: 0px;
	width:100%;
	text-align:right;
	-moz-opacity: .5;
	filter: alpha(opacity=50);
	visibility:hidden;
}
.id {
	float:right;
	width: 50px;;
	background-color:#FFFFFF;
	margin: 20px;
	padding: 10px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: bold;
	letter-spacing: -1px;
	border:double 3px #FFFFFF;
}
a:link {
	color: #000000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #000000;
}
a:hover {
	text-decoration: none;
	color: #000000;
}
a:active {
	text-decoration: none;
	color: #000000;
}
-->
</style>
</head>

<body onload="resizetoimage();">

<a href="javascript:window.close()" onMouseOver="toggleBox('closelayer',1);" onMouseOut="toggleBox('closelayer',0);"><img src="<?=$path;?>" alt="<?=$path;?>" border="0">
<div id="closelayer" class="od"><div class="id">close&nbsp;[x]</div></div></a>

</body>
</html>
