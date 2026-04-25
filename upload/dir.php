<?php 
//
// r16 17.03.2005
//
error_reporting(E_ALL ^ E_NOTICE);
$v = '2 17.03.2005';

require ( 'include/version.inc.php' );
require ( 'include/reverse.inc.php' );
if ( isset( $_GET['contentfolder'] ) ) $contentfolder = $_GET['contentfolder'];
if ( !isset( $contentfolder ) or !is_dir( $contentfolder ) ) $contentfolder = 'content';
if ( is_dir ( $contentfolder )) {
				chdir( $contentfolder );} else {
				die ( "Can't open $contentfolder" );
} 
$sort="";
if (isset($_GET['sort'])) $sort =$_GET['sort'];


$handle = opendir( '.' );
$groups = array();

while ( $entry = readdir( $handle ) ) {
				if ( is_dir( $entry ) and ( $entry != '.' ) and ( $entry != '..' ) ) {
								// echo '<groupfolder name="',$entry,'">';
								$groups[] = $entry;
				} 
} 

if ( $sort == 'na' ) {
				usort ( $groups, "cmp" );
} elseif ( $sort == 'nd' ) {
				usort( $groups, "cmp" );
				$groups = reverse( $groups );
} elseif ( $sort == 'rnd' ) {
				shuffle ( $groups );
} elseif ( $sort == 'da' ) {
} else {
				$groups = reverse ( $groups );
} 

echo '<?xml version="1.0" ?><index>';
foreach ( $groups as $entry ) {
							echo '<groupfolder name="',$entry,'" perm="'.substr(sprintf('%o', fileperms($entry)), -3).'">';
				$folders=array();
				$handle=opendir($entry);
				while ($entry2=readdir($handle))
				{
					if (is_dir($entry.'/'.$entry2) and ($entry2!='.') and ($entry2!='..'))
					{
						$folders[]=$entry2;	
					}

				}


				if ( $sort == 'na' ) {
								usort ( $folders, "cmp" );
				} elseif ( $sort == 'nd' ) {
								usort ( $folders, "cmp" );
								$folders = reverse( $folders );
				} elseif ( $sort == 'rnd' ) {
								shuffle ( $folders );
				} elseif ( $sort == 'da' ) {
				} else {
								$folders = reverse ( $folders );
				} 

				foreach ( $folders as $entry2 ) {
								$amount = 0;
								$handle3 = opendir( $entry . '/' . $entry2 );
								while ( $entry3 = readdir( $handle3 ) ) {
												if ( ( strtolower( substr ( $entry3, -4, 4 ) ) == ".jpg" ) and ( substr( $entry3, 0, 3 ) != "tn_" ) ) $amount++;
								} 
				echo '<folder path="',$contentfolder,'/',$entry,'/',$entry2,'/" name="',$entry2,'" amount="'.$amount.'" perm="'.substr(sprintf('%o', fileperms($entry.'/'.$entry2)), -3).'"/>';
				} 

				echo '</groupfolder>';
} 

echo '</index>';

?>
