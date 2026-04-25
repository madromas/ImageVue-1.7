<?php 


if ( isset( $_GET['check'] ) ) {
				if ( getenv( "SCRIPT_NAME" ) )  {
								$path = getenv( "SCRIPT_NAME" );
				} elseif (  getenv( "PHP_SELF" ) )  {
								$path = getenv( "PHP_SELF" );
				}
				$file = basename( $path );
				if ( !file_exists( $file ) or !isset ( $file ) ) {
								echo "<b>don't know my name :(<br><b>";
				} else echo "<b>", $file, "</b> ";

				echo $v;
				if ( file_exists( $file ) ) echo "<br>", md5_file( $file );
				die();
} 
?>