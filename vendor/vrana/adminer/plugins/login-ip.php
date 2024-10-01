<?php

/** Check IP address and allow empty password
*/
class AdminerLoginIp {
	/** @access protected */
	var $ips;
	/** @access protected */
	var $forwarded_for;
	
	/** Set allowed IP addresses
	* @param array IP address prefixes
	* @param array X-Forwarded-For prefixes if IP address matches, empty array means anything
	*/
	function __construct($ips, $forwarded_for = array()) {
		$this->ips = $ips;
		$this->forwarded_for= $forwarded_for;
	}

	function login($login, $password) {
		foreach ($this->ips as $ip) {
			if (strncasecmp($_SERVER["REMOTE_ADDR"], $ip, strlen($ip)) == 0) {
				if (!$this->forwarded_for) {
					return true;
				}
				if ($_SERVER["HTTP_X_FORWARDED_FOR"]) {
					foreach ($this->forwarded_for as $forwarded_for) {
						if (strncasecmp(preg_replace('~.*, *~', '', $_SERVER["HTTP_X_FORWARDED_FOR"]), $forwarded_for, strlen($forwarded_for)) == 0) {
							return true;
						}
					}
				}
			}
		}
		return false;
	}

}
