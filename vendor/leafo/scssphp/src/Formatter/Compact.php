<?php
/**
 * SCSSPHP
 *
 *
 *
 */

namespace Leafo\ScssPhp\Formatter;

use Leafo\ScssPhp\Formatter;

/**
 * Compact formatter
 *
 */
class Compact extends Formatter
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->indentLevel = 0;
        $this->indentChar = '';
        $this->break = '';
        $this->open = ' {';
        $this->close = "}\n\n";
        $this->tagSeparator = ',';
        $this->assignSeparator = ':';
        $this->keepSemicolons = true;
    }

    /**
     * {@inheritdoc}
     */
    public function indentStr()
    {
        return ' ';
    }
}
