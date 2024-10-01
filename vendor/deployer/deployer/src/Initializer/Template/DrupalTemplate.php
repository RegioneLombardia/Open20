<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Initializer\Template;

/**
 * @codeCoverageIgnore
 */
class DrupalTemplate extends FrameworkTemplate
{
    protected function getRecipe()
    {
        return 'drupal8';
    }
}
