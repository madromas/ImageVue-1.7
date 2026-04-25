<?
error_reporting(E_ALL^E_NOTICE^E_WARNING);
include('config.inc.php');
include('../include/secure.inc.php');
include('passwords.php');  
	

$success='';
if (isset( $_GET['path'])) 
{ 	
	$getpath=$_GET['path']; 
	$pass=$_GET['pass'];
	
	

	displayForm (securePath($getpath),$pass);
	

}
elseif(isset ($_FILES['uploadFile']) && isset ($_POST['getpath']))
{ 	
	$pass=$_POST['pass'];
	
	if (!in_array($pass, array_keys($data))) die ('Error.');
	$path=securePath($_POST['getpath']);
	if (!checkpaths($data[$pass],$path)) {die ('Error.');} 
	$path=securePath($path);

	

	if ($_FILES['uploadFile']['error']) {
		echo "upload error";
	} else 
	{
	
		$file_name = securePath($_FILES['uploadFile']['name']);
		$tmp_name = securePath($_FILES['uploadFile']['tmp_name']);

			
			$file_name = stripslashes($file_name);
			
			$file_name = str_replace("'","",$file_name);
			$copy=0;
			if (in_array(getExt($file_name),$extensions)) {
			
			$copy = move_uploaded_file($tmp_name,'../'.$path.$file_name);
			} 
			 // check if successfully copied
			if($copy)
			{
				 echo "$file_name | uploaded<br>";
				 
			}else
			{
				echo "$file_name | failed<br>";
				
			}

		 }



		displayForm ($path,$pass);


	

}
else if(isset ($_POST['path']))
{		
		$amount=$_POST['amount'];
		$amount=explode('|',$amount);	
		$path=$_POST['path'];

		$path=securePath($path);
		$pass=$_POST['pass'];
	
		if (!in_array($pass, array_keys($data))) die ('Wrong password.');
		if (!checkpaths($data[$pass],securePath($_POST['getpath']))) {die ('Error');} 
		
		$upStatus='';
		foreach($amount as $i) 
		{
				$file_name = securePath($_FILES['uploadFile'.$i]['name']);
			$tmp_name = securePath($_FILES['uploadFile'.$i]['tmp_name']);

			

			$file_name = stripslashes($file_name);
			
			
			if($upStatus!='') $upStatus.='*';
			$upStatus.=$file_name;
			$fsize=filesize($tmp_name);
			if(!$fsize) $fsize=0;
			$upStatus.='*'.$fsize;
			$file_name = str_replace("'","",$file_name);
			if (in_array(getExt($file_name),$extensions)) {
			$copy = @move_uploaded_file($tmp_name,'../'.$path.$file_name);
			} else {
				$copy=0;
			}
			 // check if successfully copied
			if($copy)
			{
				 //echo "$file_name | uploaded<br>";
				 		$upStatus.='*1';
			}else
			{
				//echo "$file_name | failed<br>";
						$upStatus.='*0';
			}

		 }

		$success='onLoad="set_success(\''.$upStatus.'\')"';

include('form.inc.php');
} else { $path=securePath($_REQUEST['path']);
include('form.inc.php');
}



function displayForm($getpath,$pass) {
	?>

	<html>
	<head>
	<title>Image Upload</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #333333;
}
body {
	background-color: #EFEFEF;
}
-->
</style>
	</head>
	<body>
	<table width="100%" height="90%"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center">
	Uploading to <b><?=$getpath;?></b><br><br>
	<form enctype="multipart/form-data" method="post" name="form" action="upload.php">
	<input type="file"  name="uploadFile" id="uploadFile">
	<input type="submit" value="Upload">
	<input type="hidden" value="<?=$getpath;?>" name="getpath" id="getpath">
	<input type="hidden" value="<?=$pass;?>" name="pass" id="pass">
	</form>
	</td>
      </tr>
    </table>
	</body>
	</html>
	<?
	}
?>