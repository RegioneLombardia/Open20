<?php
/**
 * SCSSPHP
 *
 *
 *
 */

namespace Leafo\ScssPhp\Formatter;

/**
 * Output block
 *
 */
class OutputBlock
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var integer
     */
    public $depth;

    /**
     * @var array
     */
    public $selectors;

    /**
     * @var array
     */
    public $lines;

    /**
     * @var array
     */
    public $children;

    /**
     * @var \Leafo\ScssPhp\Formatter\OutputBlock
     */
    public $parent;

    /**
     * @var string
     */
    public $sourceName;

    /**
     * @var integer
     */
    public $sourceLine;

    /**
     * @var integer
     */
    public $sourceColumn;
}
