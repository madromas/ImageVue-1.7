<?php
//
// r16 16.03.2005
//

function reverse($array) {
	$out=array();

	for ($i=count($array)-1; $i>=0; $i--)
		$out[]=$array[$i];
	return $out;

}

function cmp($a, $b) { 
   if ($a == $b) { 
       return 0; 
   }
   return (strtolower($a) < strtolower($b)) ? -1 : 1; 
} 
?>