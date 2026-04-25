<?php
// 
// r16 16.03.2005
//

class Config{
	var $PATH             = null;
	var $VARS             = array();
	var $SYNCHRONIZE      = false;
	var $PROCESS_SECTIONS = true;
	var $PROTECTED_MODE   = true;
	var $ERRORS           = array();

	function Config( $path=null, $synchronize=false, $process_sections=true){
		// check whether to enable processing-sections or not
		if ( isset( $process_sections)) $this->PROCESS_SECTIONS = $process_sections;
		// check whether to enable synchronisation or not
		if ( isset( $synchronize)) $this->SYNCHRONIZE = $synchronize;
		// if a path was passed and file exists, try to load it
		if ( $path!=null) {
			// set passed path as class-var
			$this->PATH = $path;
			if ( !is_file( $path)) {
				// conf-file seems not to exist, try to create an empty new one
				$fp_new = @fopen( $path, 'w', false);
				if ( !$fp_new) {
					$err = "ConfigMagik() - Could not create new config-file('$path'), error.";
					array_push( $this->ERRORS, $err);
					die( $err);
				}else{
					fclose( $fp_new);
				}
			}else{
				// try to load and parse ini-file at specified path
				$loaded = $this->load( $path);
				if ( !$loaded) exit();
			}
		}
	}

	function get( $key=null, $section=null){
		if ( $section) $this->PROCESS_SECTIONS = true;
		else           $this->PROCESS_SECTIONS = false;
		// get requested value
		if ( $this->PROCESS_SECTIONS) {
			$value = $this->VARS[$section][$key];
		}else{
			$value = $this->VARS[$key];
		}
		if ( $value===false) {
			return null;
		}
		// return found value 
		return $value;
	}
function set( $key, $value, $section=null){
		// when sections are enabled and user tries to genarate non-sectioned vars, 
		// throw an error, this is definitely not allowed.
		if ( $this->PROCESS_SECTIONS and !$section) {
			$err = "set() - Passed no section when in section-mode, nothing was set.";
			array_push( $this->ERRORS, $err);
			return false;
		}
		// check if section was passed
		if ( $section===true) $this->PROCESS_SECTIONS = true;
		// set key with given value in given section (if enabled)
		if ( $this->PROCESS_SECTIONS) {
			$this->VARS[$section][$key] = $value;
		}else{
			$this->VARS[$key]           = $value;
		}
		// synchronize memory with file when enabled
		if ( $this->SYNCHRONIZE) {
			$this->save();
		}
		return true;
	}
	
	function removeKey( $key, $section=null){
		// check if section was passed and it's valid
		if ( $section!=null){
			if ( in_array( $section, array_keys( $this->VARS))==false){
				$err = "removeKey() - Could not find section('$section'), nothing was removed.";
				array_push( $this->ERRORS, $err);
				return false;
			}
			// look if given key exists in given section
			if ( in_array( $key, array_keys( $this->VARS[$section]))===false) {
				$err = "removeKey() - Could not find key('$key'), nothing was removed.";
				array_push( $this->ERRORS, $err);
				return false;
			}
			// remove key from section
			$pos = array_search( $key, array_keys( $this->VARS[$section]), true);
			array_splice( $this->VARS[$section], $pos, 1);
			return true;
		}else{
			// look if given key exists
			if ( in_array( $key, array_keys( $this->VARS))===false) {
				$err = "removeKey() - Could not find key('$key'), nothing was removed.";
				array_push( $this->ERRORS, $err);
				return false;
			}
			// remove key (sections disabled)
			$pos = array_search( $key, array_keys( $this->VARS), true);
			array_splice( $this->VARS, $pos, 1);
			// synchronisation-stuff
			if ( $this->SYNCHRONIZE) $this->save();
			// return
			return true;
		}
	}
	
	function removeSection( $section){
		// check if section exists
		if ( in_array( $section, array_keys( $this->VARS), true)===false) {
			$err = "removeSection() - Section('$section') could not be found, nothing removed.";
			array_push( $this->ERRORS, $err);
			return false;
		}
		// find position of $section in current config
		$pos = array_search( $section, array_keys( $this->VARS), true);
		// remove section from current config
		array_splice( $this->VARS, $pos, 1);
		// synchronisation-stuff
		if ( $this->SYNCHRONIZE) $this->save();
		// return
		return true;
	}

	function load( $path=null){
		// if path was specified, check if valid else abort
		if ( $path!=null and !is_file( $path)) {
			$err = "load() - Path('$path') is invalid, nothing loaded.";
			array_push( $this->ERRORS, $err);
			echo $err;
			return false;
		}elseif ( $path==null){
			// no path was specified, fall back to class-var
			$path = $this->PATH;
		}
		/* 
		 * PHP's own method is used for parsing the ini-file instead of own code. 
		 * It's robust enough ;-)
		 */
		$this->VARS = parse_ini_file( $path, $this->PROCESS_SECTIONS);
		return true;
	}

	function save( $path=null){
		// if no path was specified, fall back to class-var
		if ( $path==null) $path = $this->PATH;

		$content  = "";
		
		// PROTECTED_MODE-prefix
		if ( $this->PROTECTED_MODE) {
			$content .= "<?PHP\n; /*\n; -- BEGIN PROTECTED_MODE\n";
		}
		
		// config-header
		$content .= "; This files was automagically generated by ConfigMagik\n";
		$content .= "; Do not edit this file by hand, use ConfigMagik instead.\n";
		$content .= "; Last modified: ".date('d M Y H:i s')."\n";
		
		// check if there are sections to process
		if ( $this->PROCESS_SECTIONS) {
			foreach ( $this->VARS as $key=>$elem) {
				$content .= "[".$key."]\n";
				foreach ( $elem as $key2=>$elem2) {
					$content .= $key2." = \"".$elem2."\"\n";
				}
			}
		}else{
			foreach ( $this->VARS as $key=>$elem) {
				$content .= $key." = \"".$elem."\"\n";
			}
		}
		
		// add PROTECTED_MODE-ending
		if ( $this->PROTECTED_MODE) {
			$content .= "\n; -- END PROTECTED_MODE\n; */\n?>\n";	
		}

		// write to file
		if ( !$handle = @fopen( $path, 'w')) {
			$err = "save() - Could not open file('$path') for writing, error.";
			array_push( $this->ERRORS, $err);
			return false;
		}
		if ( !fwrite( $handle, $content)) {
			$err = "save() - Could not write to open file('$path'), error.";
			array_push( $this->ERRORS, $err);
			return false;
		}else{
			// push a message onto error-stack
			$err = "save() - Sucessfully saved to file('$path').";
			array_push( $this->ERRORS, $err);
		}
		fclose( $handle);
		return true;
	}
	
	function toString( $output_type='TEXT', $sect='',$urle=false){
		$out='';
		if (isset($sect)) $sect=strtoupper($sect);
		// check requested output-type
		if ( strtoupper( $output_type)!=='TEXT' and strtoupper( $output_type)!=='HTML' and strtoupper( $output_type)!=='XML') {
			$err = "toString() - Unknown OutputType('$output_type') was requested, falling back to TEXT.";
			array_push( $this->ERRORS, $err);
			$output_type = 'TEXT';
		}
		if ( strtoupper( $output_type) === 'TEXT') {
			// render object as TEXT
			
			$sections = $this->listSections();
			if (isset ($sect) && (in_array($sect,$sections))) $sections = array ($sect); 
			foreach ( $sections as $section){
			
				$keys = $this->listKeys( $section);
				foreach ( $keys as $key){
					$val  = $this->get( $key, $section);
					if ($urle) $var=urlencode($var);
					$out .= $key."=".$val."&";

				}
			}


			
			
			
			
			return $out;
		}elseif ( strtoupper( $output_type) === 'XML')
		{
			$out="<index>\n";
			$sections = $this->listSections();
			if (isset ($sect) && (in_array($sect,$sections))) $sections = array ($sect); 
			foreach ( $sections as $section){
				if (!isset($sect)) $out .= "<section name=\"".$section."\"\n";
				$keys = $this->listKeys( $section);
				foreach ( $keys as $key){
					$val  = $this->get( $key, $section);
					$out .= "\t<item name=\"".$key."\" value=\"".$val."\"/>\n";

				}
				if (!isset ($sect)) $out .= "<section/>\n";
			
			}
			$out.="<index/>";
			return $out;
			
		
		}elseif ( strtoupper( $output_type) === 'HTML'){
			// render object as HTML
			$out  = "<table cellpadding=5 cellspacing=0 style='border:1px solid grey;' width=60%>\n";
			if ( $this->PROCESS_SECTIONS && !isset($sect)){
				// render with sections
				//$out .= "\t<tr><th style='font-family: verdana; font: 11px;'><b>Section</b></th><th style='font-family: verdana; font: 11px;'><b>Key</b></th><th style='font-family: verdana; font: 11px;'><b>Value</b></th></tr>\n";
				$sections = $this->listSections();
				if (isset ($sect) && (in_array($sect,$sections))) $sections = array ($sect); 
				$num_sections = 0;
				$num_keys     = 0;
				foreach ( $sections as $section){
					$out .= "\t<tr><td colspan=3 style='font-family: verdana; font: 11px;'><b>$section</b></td></tr>\n";
					$keys = $this->listKeys( $section);
					foreach ( $keys as $key){
						$val  = $this->get( $key, $section);
						$out .= "\t<tr><td>&nbsp;</td><td style='font-family: verdana; font: 10px;'>$key</td><td style='font-family: verdana; font: 10px;'>$val</td></tr>\n";
						$num_keys++;
					}
					$num_sections++;
				}
				// summary of table (with sections)
				$out .= "\t<tr><td colspan=3 align=right  style='font-family: verdana; font: 10px;'>There are <b>$num_keys keys</b> in <b>$num_sections sections</b>.</td></tr>\n";
			}else{
				// render without sections
				$keys     = $this->listKeys($sect);
				$num_keys = 0;
				//$out .= "\t<tr><th>Key</th><th>Value</th></tr>\n";
				foreach ( $keys as $key){
					$val  = $this->get( $key,$sect);
					//$out .= "\t<tr><td>$key</td><td>$val</td></tr>\n";
											$out .= "<tr><td style='font-family: verdana; font: 10px;'>$key</td><td style='font-family: verdana; font: 10px;'>$val</td></tr>\n";

					$num_keys++;
				}
				// summary of table (without sections)
				$out .= "\t<tr><td colspan=2 align=right style='font-family: verdana; font: 10px;'>There are <b>$num_keys keys</b>.</td></tr>\n";
			}
			
			// close table
			$out .= "</table>";
			return $out;
		}
	}
	
	function listKeys( $section=null){
		// check if section was passed
		if ( $section!==null){
			// check if passed section exists
			$sections = $this->listSections();
			if ( in_array( $section, $sections)===false) {
				$err = "listKeys() - Section('$section') could not be found.";
				array_push( $this->ERRORS, $err);
				return false;
			}
			// list all keys in given section
			$list = array();
			$s=$this->VARS[$section];
			$all  = array_keys( $s );
			foreach ( $all as $possible_key)
				 
				if ( !is_array( $s[$possible_key])) 
					array_push( $list, $possible_key);
				
			
			return $list;
		}else{
			// list all keys (section-less)
			return array_keys( $this->VARS);
		}
	}
	
	function listSections(){
		$list = array();
		// separate sections from normal keys
		$all  = array_keys( $this->VARS);
		foreach ( $all as $possible_section){
			if ( is_array( $this->VARS[$possible_section])) {
				array_push( $list, strtoupper($possible_section));
			}
		}
		return $list;
	}
}
?>