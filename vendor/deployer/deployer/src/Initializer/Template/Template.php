<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Initializer\Template;

/**
 * Abstract template for create deployer configuration.
 *
 */
abstract class Template implements TemplateInterface
{
    /**
     * {@inheritDoc}
     */
    public function initialize($filePath, $params)
    {
        $params = array_merge([
            'repository' => 'git@domain.com:username/repository.git',
            'allow_anonymous_stats' => true,
        ], $params);

        $content = $this->getTemplateContent($params);
        file_put_contents($filePath, $content);
    }

    /**
     * Get content of template.
     *
     * @param array $params
     * @return string
     */
    abstract protected function getTemplateContent($params);
}
