<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Validator;

interface ValidatorPluginManagerAwareInterface
{
    /**
     * Set validator plugin manager
     *
     * @param ValidatorPluginManager $pluginManager
     */
    public function setValidatorPluginManager(ValidatorPluginManager $pluginManager);

    /**
     * Get validator plugin manager
     *
     * @return ValidatorPluginManager
     */
    public function getValidatorPluginManager();
}
