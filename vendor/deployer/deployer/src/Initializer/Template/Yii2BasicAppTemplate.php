<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Initializer\Template;

/**
 * Generate a Yii2 app basic deployer configuration.
 *
 * @codeCoverageIgnore
 */
class Yii2BasicAppTemplate extends FrameworkTemplate
{
    protected function getRecipe()
    {
        return 'yii2-app-basic';
    }
}
