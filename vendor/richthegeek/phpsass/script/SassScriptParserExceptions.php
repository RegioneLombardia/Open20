<?php
/* SVN FILE: $Id$ */
/**
 * SassScript Parser exception class file.
 * @package      PHamlP
 * @subpackage  Sass.script
 */

require_once(dirname(__FILE__).'/../SassException.php');

/**
 * SassScriptParserException class.
 * @package      PHamlP
 * @subpackage  Sass.script
 */
class SassScriptParserException extends SassException {}

/**
 * SassScriptLexerException class.
 * @package      PHamlP
 * @subpackage  Sass.script
 */
class SassScriptLexerException extends SassScriptParserException {}

/**
 * SassScriptOperationException class.
 * @package      PHamlP
 * @subpackage  Sass.script
 */
class SassScriptOperationException extends SassScriptParserException {}

/**
 * SassScriptFunctionException class.
 * @package      PHamlP
 * @subpackage  Sass.script
 */
class SassScriptFunctionException extends SassScriptParserException {}
