<?php

/** Disable version checker
*/
class AdminerVersionNoverify {
	
	function head() {
		echo script("verifyVersion = function () {};");
		return true;
	}
	
}
