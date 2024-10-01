<?php

/** Dump to XML format in structure <database name=""><table name=""><column name="">value
*/
class AdminerDumpXml {
	/** @access protected */
	var $database = false;
	
	function dumpFormat() {
		return array('xml' => 'XML');
	}

	function dumpTable($table, $style, $is_view = 0) {
		if ($_POST["format"] == "xml") {
			return true;
		}
	}
	
	function _database() {
		echo "</database>\n";
	}
	
	function dumpData($table, $style, $query) {
		if ($_POST["format"] == "xml") {
			if (!$this->database) {
				$this->database = true;
				echo "<database name='" . h(DB) . "'>\n";
				register_shutdown_function(array($this, '_database'));
			}
			$connection = connection();
			$result = $connection->query($query, 1);
			if ($result) {
				while ($row = $result->fetch_assoc()) {
					echo "\t<table name='" . h($table) . "'>\n";
					foreach ($row as $key => $val) {
						echo "\t\t<column name='" . h($key) . "'" . (isset($val) ? "" : " null='null'") . ">" . h($val) . "</column>\n";
					}
					echo "\t</table>\n";
				}
			}
			return true;
		}
	}

	function dumpHeaders($identifier, $multi_table = false) {
		if ($_POST["format"] == "xml") {
			header("Content-Type: text/xml; charset=utf-8");
			return "xml";
		}
	}

}
