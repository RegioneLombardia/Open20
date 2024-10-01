<?php

/** Log all queries to SQL file (manual queries through SQL command are not logged)
*/
class AdminerSqlLog {
	/** @access protected */
	var $filename;
	
	/**
	* @param string defaults to "$database.sql"
	*/
	function __construct($filename = "") {
		$this->filename = $filename;
	}
	
	function messageQuery($query, $time, $failed = false) {
		$this->_log($query);
	}

	function sqlCommandQuery($query) {
		$this->_log($query);
	}

	function _log($query) {
		if ($this->filename == "") {
			$adminer = adminer();
			$this->filename = $adminer->database() . ".sql"; // no database goes to ".sql" to avoid collisions
		}
		$fp = fopen($this->filename, "a");
		flock($fp, LOCK_EX);
		fwrite($fp, $query);
		fwrite($fp, "\n\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	
}
