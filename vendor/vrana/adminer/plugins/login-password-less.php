<?php

/** Enable login for password-less database
*/
class AdminerLoginPasswordLess {
	/** @access protected */
	var $password_hash;
	
	/** Set allowed password
	* @param string result of password_hash
	*/
	function __construct($password_hash) {
		$this->password_hash = $password_hash;
	}

	function credentials() {
		$password = get_password();
		return array(SERVER, $_GET["username"], (password_verify($password, $this->password_hash) ? "" : $password));
	}
	
	function login($login, $password) {
		if ($password != "") {
			return true;
		}
	}

}
