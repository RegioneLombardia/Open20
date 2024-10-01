<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Initializer\Template;

/**
 * Generate a Yii deployer configuration.
 *
 * @codeCoverageIgnore
 */
class Yii2AdvancedAppTemplate extends FrameworkTemplate
{
    protected function getRecipe()
    {
        return 'yii2-app-advanced';
    }
}
