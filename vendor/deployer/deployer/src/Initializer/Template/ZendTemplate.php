<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Initializer\Template;

/**
 * @codeCoverageIgnore
 */
class ZendTemplate extends FrameworkTemplate
{
    protected function getRecipe()
    {
        return 'zend_framework';
    }
}
