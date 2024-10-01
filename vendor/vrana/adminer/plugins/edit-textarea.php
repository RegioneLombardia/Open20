<?php

/** Use <textarea> for char and varchar
*/
class AdminerEditTextarea {

	function editInput($table, $field, $attrs, $value) {
		if (preg_match('~char~', $field["type"])) {
			return "<textarea cols='30' rows='1' style='height: 1.2em;'$attrs>" . h($value) . '</textarea>';
		}
	}

}
