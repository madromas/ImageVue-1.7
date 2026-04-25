<?
include('header.inc.php');
error_reporting(E_ALL);
echo '<b>../content</b> Probe:<br><br>';
echo '<b>opendir()<b/>:';
if($dhandle=opendir('../content')) { echo "<span class='green'>OK</span>"; }
else { echo "<span class='red'FAIL</span>"; }
echo '<br><b>chdir()<b/>:';
if(chdir('../content'))
{
	echo "<span class='green'>OK</span>"; 
	echo '<br><b>fopen()<b/>:';
	if($fh=fopen('test.tmp','w')) 
	{
		echo "<span class='green'>OK</span>"; 
		echo '<br><b>writing()<b/>:';
		if(fputs($fh,'test'))
		{ 
			echo "<span class='green'>OK</span>"; 
			fclose($fh);
			unlink('test.tmp');
		}
		else 
		{
			echo "<span class='red'FAIL</span>"; 
		}
	}
	else 
	{ 
	echo "<span class='red'FAIL</span>"; 
	}
	
}
else { echo "<span class='red'>FAIL</span>"; }
closedir($dhandle);
?>