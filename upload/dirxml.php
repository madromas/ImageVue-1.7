<?php 
//
// r16.3 30.03.2005
//
error_reporting(E_ALL ^ E_NOTICE);
$v = 'v3 30.03.2005';

require ( 'include/version.inc.php' );
require ( 'include/reverse.inc.php' );
require ( 'include/jpgchk.inc.php'  );
if ( isset( $_GET['path'] ) ) {
				$path = $_GET['path'];
} else {
				die ( "No path given" );
} 
$dir_handle = opendir( $path ) or die( "Unable to open $path" );
$sort = "";
if ( isset( $_GET['sort'] ) ) $sort = $_GET['sort'];

$files = array();

echo '<?xml version="1.0" ?><index>';

while ( $file = readdir( $dir_handle ) ) {
				if ( $file != '.' and $file != '..' and substr( $file, 0, 3 ) != "tn_" and strtolower( substr ( $file, -4, 4 ) ) == ".jpg" )
				$files[] = $file;
} 

if ( count( $files ) ) {
				if ( $sort == 'na' ) {
								usort ( $files, 'cmp' );
				} elseif ( $sort == 'nd' ) {
								usort ( $files, 'cmp' );
								$files = reverse ( $files );
				} elseif ( $sort == 'rnd' ) {
								shuffle ( $files );
				} elseif ( $sort == 'da' ) {
				} 
				if ( $sort == 'dd' ) {
								$files = reverse( $files );
				} ;

				foreach ( $files as $file ) {
								list( $w, $h ) = GetImageSize( $path . '/' . $file );
								echo '<image name="', $file, '" width="', $w, '" height="', $h, '" size="', filesize( $path . '/' . $file ), '" date="', date( 'mdy', filemtime( $path . '/' . $file ) );
		if (!file_exists($path .'/tn_'.$file)) echo '" nothumb="1';
		
		if (jpgchk($path .'/'.$file)) echo '" progressive="1';
		echo '" />';
				} 
} 
closedir( $dir_handle );
echo "</index>";

?>