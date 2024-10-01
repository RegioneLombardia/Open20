<?php

namespace luya\generic;

use luya\cms\base\PhpBlock;

/**
 * Base block for all generic blocks.
 *
 */
abstract class BaseGenericBlock extends PhpBlock
{
    /**
     * @inheritdoc
     */
    public function getViewPath()
    {
        return  dirname(__DIR__) . '/src/views/blocks';
    }
}
