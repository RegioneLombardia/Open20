<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Initializer\Template;

/**
 * @codeCoverageIgnore
 */
class CodeIgniterTemplate extends FrameworkTemplate
{
    protected function getRecipe()
    {
        return 'codeigniter';
    }
}
