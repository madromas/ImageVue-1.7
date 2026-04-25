<?
//
// Imagevue prog jpg checker
// r16.3 30.03.2005
//
error_reporting(E_ALL ^ E_NOTICE);
function jpgchk($file){
global $offset;
	global $data;
	global $length;
$f=fopen ($file,'rb') or die ('cant open file');
$length=1024;
$data=fread($f,$length);
fclose($f);


$offset=0;

$xx=getData(11). '.'.getData($offset);
$vals=array('pixels','dot/inch','dot/mm');

$units=$vals[getData($offset)];

$xt=getData($offset,2); $yt=getData($offset,2);
$xt=getData($offset); $yt=getData($offset);

$skip=$xt*$yt;

$offset+=$skip;

$mem=$offset;
while (!isset($stop)){
 if (getData($offset)==255) {

	$m=$offset;
	$subhead=getData($m,1,'hex');

	$sh=getData($m,1);
	$off=getData($offset,2);

	$m=$offset;

	if ($off==17 && $subhead=='c2') return (1);


	$offset=$m+$off-2;

} else { $stop=1;}
}

return(0);
}

function getData ($off,$len=1,$type='dec') {
	global $offset;
	global $data;
	global $length;
	if ($type=='dec') $total=0;
	if ($type=='hex' or $type=='str' or $type=='str') $total='';

	for ($i=0; $i<$len; $i++)
	{
		$idx=$off+$i;

		if ($type=='dec')
		{
			if ($idx<$length)
			{
				if ($len<=1 or ($i==($len-1)))
				{
					 $total+=ord($data[$off+$i]);
				} else
				{
					 $total+=ord($data[$off+$i])*(256^($len-$i-1));
				}
			}
		}

		if ($type=='hex')
		{
			if ($idx<$length)
			{
				$chr=dechex(ord($data[$idx]));
				if (strlen($chr)==1) $chr='0'.$chr;
				$total.=$chr;
			}
		}

	}
	$offset=$off+$i;
	return chop($total);



}
?>