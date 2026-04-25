<?php
//
// r16 16.03.2005
//

function getPlatform() {

 /*
    $OS[0] = 'Any';
    $OS[1] = 'Win95';
    $OS[2] = 'Win98';
    $OS[3] = 'WinME';
    $OS[4] = 'WinNT';
    $OS[5] = 'Win2000';
    $OS[6] = 'Linux';
    $OS[7] = 'Mac';
*/	

	$OS=array ('Any','WIN','WIN','WIN','WIN','WIN','Linux','MAC');

	return $OS[getAgentOS($_SERVER["HTTP_USER_AGENT"])];

}

function getBrowser() {

    /*
    $browser[0] = 'Any';
    $browser[1] = 'IE2.x';
    $browser[2] = 'IE3.x';
    $browser[3] = 'IE4.x';
    $browser[4] = 'IE5.x';
    $browser[5] = 'IE6.x';
	$browser[6] = 'IE7.x';
    $browser[7] = 'NS2.x';
    $browser[8] = 'NS3.x';
    $browser[9] = 'NS4.x';
    $browser[10] = 'NS5.x';
    $browser[11] = 'NS6.x';
    $browser[12] = 'Lynx';
    $browser[13] = 'Opera';
*/
	$browser=array ('Any','IE','IE','IE','IE','IE','IE','NS','NS','NS','NS','Lynx','Opera');
	return $browser[getAgentBrowser($_SERVER["HTTP_USER_AGENT"])];

}


function getAgentBrowser ($sUserAgent){
    $vbro = array('MSIE 2.'=>1, 'MSIE 3.'=>2, 'MSIE 4.'=>3, 'MSIE 5.'=>4, 'MSIE 6.'=>5, 'MSIE 7.'=>6, 'Lynx'=>12, 'Opera'=>13, 'Netscape6'=>11, 'Mozilla/2.'=>7, 'Mozilla/3.'=>8, 'Mozilla/4.'=>9, 'Mozilla/5.'=>10);

    $ibro = 0;
    foreach($vbro as $sbro=>$i){
        if( strpos($sUserAgent, $sbro) === false){
        }else{
            $ibro = $i;
            break;
        }
    }
    return ($ibro);

}

function getAgentOS ($sUserAgent){
    $vos = array('Windows 2000'=>5, 'Windows NT'=>4, 'WinNT'=>4, 'Win 9x 4.90'=>3, 'Windows 98'=>2, 'Win98'=>2, 'Windows 95'=>1, 'Win95'=>1, 'Linux'=>6, 'Macintosh'=>7, 'Mac_PowerPC'=>7);

    $ios = 0;
    foreach($vos as $sos=>$i){
        if( strpos($sUserAgent, $sos) > 0){
            $ios = $i;
            break;
        }
    }
    return ($ios);

}
?>