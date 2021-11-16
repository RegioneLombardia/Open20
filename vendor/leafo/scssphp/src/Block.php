<?php
/**
 * SCSSPHP
 *
 *
 *
 */

namespace Leafo\ScssPhp;

/**
 * Block
 *
 */
class Block
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var \Leafo\ScssPhp\Block
     */
    public $parent;

    /**
     * @var string
     */
    public $sourceName;

    /**
     * @var integer
     */
    public $sourceIndex;

    /**
     * @var integer
     */
    public $sourceLine;

    /**
     * @var integer
     */
    public $sourceColumn;

    /**
     * @var array
     */
    public $selectors;

    /**
     * @var array
     */
    public $comments;

    /**
     * @var array
     */
    public $children;

    /**
     * @var \Leafo\ScssPhp\Block
     */
    public $selfParent;
}
