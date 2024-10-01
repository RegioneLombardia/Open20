<?php

/** Include current date and time in export filename
*/
class AdminerDumpDate {
	
	function dumpFilename($identifier) {
		$connection = connection();
		return friendly_url(($identifier != "" ? $identifier : (SERVER != "" ? SERVER : "localhost")) . "-" . $connection->result("SELECT NOW()"));
	}

}
