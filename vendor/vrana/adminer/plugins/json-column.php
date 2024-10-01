<?php

/** Display JSON values as table in edit
*/
class AdminerJsonColumn {
	private function _testJson($value) {
		if ((substr($value, 0, 1) == '{' || substr($value, 0, 1) == '[') && ($json = json_decode($value, true))) {
			return $json;
		}
		return $value;
	}

	private function _buildTable($json) {
		echo '<table cellspacing="0" style="margin:2px; font-size:100%;">';
		foreach ($json as $key => $val) {
			echo '<tr>';
			echo '<th>' . h($key) . '</th>';
			echo '<td>';
			if (is_scalar($val) || $val === null) {
				if (is_bool($val)) {
					$val = $val ? 'true' : 'false';
				} elseif ($val === null) {
					$val = 'null';
				} elseif (!is_numeric($val)) {
					$val = '"' . h(addcslashes($val, "\r\n\"")) . '"';
				}
				echo '<code class="jush-js">' . $val . '</code>';
			} else {
				$this->_buildTable($val);
			}
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}

	function editInput($table, $field, $attrs, $value) {
		$json = $this->_testJson($value);
		if ($json !== $value) {
			$this->_buildTable($json);
		}
	}
}
