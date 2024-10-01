<?php

/** Show comments of sql structure in more places (mainly where you edit things)
 */
class AdminerStructComments {

	function fieldName(&$field, $order = 0) {
		return '<span title="' . h($field["full_type"]) . (!empty($field["comment"]) ? ': ' . $field["comment"] : '') . '">' . h($field["field"]) . '</span>';
	}
}
