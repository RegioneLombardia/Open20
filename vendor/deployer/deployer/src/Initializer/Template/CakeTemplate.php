<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Initializer\Template;

/**
 * @codeCoverageIgnore
 */
class CakeTemplate extends FrameworkTemplate
{
    protected function getRecipe()
    {
        return 'cakephp';
    }
}
