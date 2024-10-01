<?php

/** Display constant list of servers in login form
*/
class AdminerLoginServers {
	/** @access protected */
	var $servers;
	
	/** Set supported servers
	* @param array array($description => array("server" => , "driver" => "server|pgsql|sqlite|..."))
	*/
	function __construct($servers) {
		$this->servers = $servers;
		if ($_POST["auth"]) {
			$key = $_POST["auth"]["server"];
			$_POST["auth"]["driver"] = $this->servers[$key]["driver"];
		}
	}
	
	function credentials() {
		return array($this->servers[SERVER]["server"], $_GET["username"], get_password());
	}
	
	function login($login, $password) {
		if (!$this->servers[SERVER]) {
			return false;
		}
	}
	
	function loginFormField($name, $heading, $value) {
		if ($name == 'driver') {
			return '';
		} elseif ($name == 'server') {
			return $heading . "<select name='auth[server]'>" . optionlist(array_keys($this->servers), SERVER) . "</select>\n";
		}
	}
	
}
