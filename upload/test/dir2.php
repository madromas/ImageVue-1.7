<?php

require ('../include/reverse.inc.php');

function get_mode($filepath) 
{ 
   $decperms = fileperms($filepath); 
   $octalperms = sprintf("%o",$decperms);
   return substr($octalperms,-3); 
}


$contentfolder = $_GET['contentfolder'];
if (!$contentfolder or !is_dir($contentfolder)) $contentfolder='../content';
chdir($contentfolder);

$handle = opendir('./'.urldecode($_GET['path']));

while ($entry = readdir($handle))
{
	if (is_dir($entry) and ($entry!='.') and ($entry!='..'))
	{
		$perm=get_mode($entry);

		echo '<br>groupfolder name="',$entry,'" mode="'.$perm.'"<br>';

		$handle2=opendir($entry);
		while ($entry2=readdir($handle2))
		{ 
			if (is_dir($entry.'/'.$entry2) and ($entry2!='.') and ($entry2!='..'))
			{
				
				$perm=get_mode($entry.'/'.$entry2);
				echo 'folder path="',$contentfolder,'/',$entry,'/',$entry2,'/" perm="',$perm,'"<br>';
			}
		}
	}
}


?>