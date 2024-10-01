<?php

namespace luya\bootstrap3;

use luya\cms\base\PhpBlock;

/**
 * Base Block for alle BS3 Blocks.
 *
 */
abstract class BaseBootstrap3Block extends PhpBlock
{
    /**
     * @inheritdoc
     */
    public function getViewPath()
    {
        return  dirname(__DIR__) . '/src/views/blocks';
    }
}
