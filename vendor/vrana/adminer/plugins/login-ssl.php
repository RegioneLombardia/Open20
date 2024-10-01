<?php

/** Connect to MySQL using SSL
*/
class AdminerLoginSsl {
	/** @access protected */
	var $ssl;
	
	/** 
	* @param array array("key" => filename, "cert" => filename, "ca" => filename)
	*/
	function __construct($ssl) {
		$this->ssl = $ssl;
	}
	
	function connectSsl() {
		return $this->ssl;
	}
	
}
