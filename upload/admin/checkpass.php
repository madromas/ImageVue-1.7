<?php
//
// r16 18.03.2005
//

include('passwords.php');                    
echo 'success=';

if (isset($_GET['pass'])) { $pass=strtolower ($_GET['pass']); } else { die ('none'); }

if (!in_array($pass, array_keys($data))) { echo 'none'; }
else { echo urlencode($data[$pass]); } 
		
?>