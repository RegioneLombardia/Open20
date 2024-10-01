<?php

/** Dump to Bzip2 format
* @uses bzopen(), tempnam("")
*/
class AdminerDumpBz2 {
	/** @access protected */
	var $filename, $fp;
	
	function dumpOutput() {
		if (!function_exists('bzopen')) {
			return array();
		}
		return array('bz2' => 'bzip2');
	}
	
	function _bz2($string, $state) {
		bzwrite($this->fp, $string);
		if ($state & PHP_OUTPUT_HANDLER_END) {
			bzclose($this->fp);
			$return = file_get_contents($this->filename);
			unlink($this->filename);
			return $return;
		}
		return "";
	}
	
	function dumpHeaders($identifier, $multi_table = false) {
		if ($_POST["output"] == "bz2") {
			$this->filename = tempnam("", "bz2");
			$this->fp = bzopen($this->filename, 'w');
			header("Content-Type: application/x-bzip");
			ob_start(array($this, '_bz2'), 1e6);
		}
	}

}
