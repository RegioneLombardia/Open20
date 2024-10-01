<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Initializer\Template;

/**
 * Generate a Laravel deployer configuration.
 *
 * @codeCoverageIgnore
 */
class LaravelTemplate extends FrameworkTemplate
{
    protected function getRecipe()
    {
        return 'laravel';
    }

    protected function getExtraContent()
    {
        return <<<PHP

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

PHP;
    }
}
