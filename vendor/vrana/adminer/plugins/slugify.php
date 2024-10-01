<?php

/** Prefill field containing "_slug" with slugified value of a previous field (JavaScript)
*/
class AdminerSlugify {
	/** @access protected */
	var $from, $to;

	/**
	* @param string find these characters ...
	* @param string ... and replace them by these
	*/
	function __construct($from = 'áčďéěíňóřšťúůýž', $to = 'acdeeinorstuuyz') {
		$this->from = $from;
		$this->to = $to;
	}

	function editInput($table, $field, $attrs, $value) {
		static $slugify;
		if (!$_GET["select"] && !$_GET["where"]) {
			if ($slugify === null) {
				$slugify = array();
				$prev = null;
				foreach (fields($table) as $name => $val) {
					if ($prev && preg_match('~(^|_)slug(_|$)~', $name)) {
						$slugify[$prev] = $name;
					}
					$prev = $name;
				}
			}
			$slug = $slugify[$field["field"]];
			if ($slug !== null) {
				return "<input value='" . h($value) . "' data-maxlength='$field[length]' size='40'$attrs>"
					. script("qsl('input').onchange = function () {
	var find = '$this->from';
	var repl = '$this->to';
	this.form['fields[$slug]'].value = this.value.toLowerCase()
		.replace(new RegExp('[' + find + ']', 'g'), function (str) { return repl[find.indexOf(str)]; })
		.replace(/[^a-z0-9_]+/g, '-')
		.replace(/^-|-\$/g, '')
		.substr(0, $field[length]);
};");
			}
		}
	}

}
