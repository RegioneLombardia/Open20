<?php

/** Allow using Adminer inside a frame (disables ClickJacking protection)
*/
class AdminerFrames {
	/** @access protected */
	var $sameOrigin;
	
	/**
	* @param bool allow running from the same origin only
	*/
	function __construct($sameOrigin = false) {
		$this->sameOrigin = $sameOrigin;
	}
	
	function headers() {
		if ($this->sameOrigin) {
			header("X-Frame-Options: SameOrigin");
		} elseif (function_exists('header_remove')) {
			header_remove("X-Frame-Options");
		}
	}
	
}
