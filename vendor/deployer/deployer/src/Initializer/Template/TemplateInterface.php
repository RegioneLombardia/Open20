<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Initializer\Template;

/**
 * All templates for initializer should implement this interface.
 *
 */
interface TemplateInterface
{
    /**
     * Initialize deployer
     *
     * @param string $filePath The file path for "deploy.php"
     * @param array $params
     */
    public function initialize($filePath, $params);
}
