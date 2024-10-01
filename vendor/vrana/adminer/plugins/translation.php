<?php
/* Requires this table:
CREATE TABLE translation (
	id int NOT NULL AUTO_INCREMENT, -- optional
	language_id varchar(5) NOT NULL,
	idf text NOT NULL COLLATE utf8_bin,
	translation text NOT NULL,
	UNIQUE (language_id, idf(100)),
	PRIMARY KEY (id)
);
*/

/** Translate all table and field comments, enum and set values in Editor from the translation table (inserts new translations)
*/
class AdminerTranslation {
	
	function _translate($idf) {
		static $translations, $lang;
		if ($lang === null) {
			$lang = get_lang();
		}
		if ($idf == "" || $lang == "en") {
			return $idf;
		}
		if ($translations === null) {
			$translations = get_key_vals("SELECT idf, translation FROM translation WHERE language_id = " . q($lang));
		}
		$return = &$translations[$idf];
		if ($return === null) {
			$return = $idf;
			$connection = connection();
			$connection->query("INSERT INTO translation (language_id, idf, translation) VALUES (" . q($lang) . ", " . q($idf) . ", " . q($idf) . ")");
		}
		return $return;
	}
	
	function tableName(&$tableStatus) {
		$tableStatus["Comment"] = $this->_translate($tableStatus["Comment"]);
	}
	
	function fieldName(&$field, $order = 0) {
		$field["comment"] = $this->_translate($field["comment"]);
	}
	
	function editVal(&$val, $field) {
		if ($field["type"] == "enum") {
			$val = $this->_translate($val);
		}
	}
	
}
