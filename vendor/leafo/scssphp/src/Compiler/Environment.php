<?php
/**
 * SCSSPHP
 *
 *
 *
 */

namespace Leafo\ScssPhp\Compiler;

/**
 * Compiler environment
 *
 */
class Environment
{
    /**
     * @var \Leafo\ScssPhp\Block
     */
    public $block;

    /**
     * @var \Leafo\ScssPhp\Compiler\Environment
     */
    public $parent;

    /**
     * @var array
     */
    public $store;

    /**
     * @var array
     */
    public $storeUnreduced;

    /**
     * @var integer
     */
    public $depth;
}
