<?php 
//
// r16 17.03.2005
//
$v = 'v2 17.03.2005';

require ( 'include/version.inc.php' );

if ( isset( $_GET['configfile'] ) ) {
		$conf = $_GET['configfile'];
		} else {
				$conf = 'config.ini';
				} 
				
				if ( !file_exists( $conf ) ) die ( "Error reading config file $config" );
				require_once( 'include/Config.class.php' );
				$Config = new Config( $conf, false, true );
				
				$type = ( isset( $_GET['type'] ) )?$_GET['type']:"";
				$section = ( isset( $_GET['section'] ) )?$_GET['section']:"";
				$urle = ( isset( $_GET['urlencode'] ) )?true:false;
				echo ( $Config->toString( $type, $section,$urle ) );
			
?>
