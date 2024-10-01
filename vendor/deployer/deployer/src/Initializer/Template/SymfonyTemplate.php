<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Initializer\Template;

/**
 * Generate a Symfony deployer configuration.
 *
 * @codeCoverageIgnore
 */
class SymfonyTemplate extends FrameworkTemplate
{
    protected function getRecipe()
    {
        return 'symfony';
    }

    /**
     * {@inheritDoc}
     */
    protected function getExtraContent()
    {
        return <<<PHP

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

PHP;
    }
}
