<?php

/** Hide some databases from the interface - just to improve design, not a security plugin
*/
class AdminerDatabaseHide {
	protected $disabled;
	
	/**
	* @param array case insensitive database names in values
	*/
	function __construct($disabled) {
		$this->disabled = array_map('strtolower', $disabled);
	}
	
	function databases($flush = true) {
		$return = array();
		foreach (get_databases($flush) as $db) {
			if (!in_array(strtolower($db), $this->disabled)) {
				$return[] = $db;
			}
		}
		return $return;
	}
	
}
