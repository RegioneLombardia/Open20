<?php
/**
 */

namespace Zend\Validator;

class ConfigProvider
{
    /**
     * Return configuration for this component.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Return dependency mappings for this component.
     *
     * @return array
     */
    public function getDependencyConfig()
    {
        return [
            'aliases' => [
                'ValidatorManager' => ValidatorPluginManager::class,
            ],
            'factories' => [
                ValidatorPluginManager::class => ValidatorPluginManagerFactory::class,
            ],
        ];
    }
}
