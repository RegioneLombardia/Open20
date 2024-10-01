<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments
 * @category   CategoryName
 */

namespace open20\socialwall;

/**
 * Class SocialwallModuleTrait
 * @package open20\socialwall
 */
trait SocialwallModuleTrait
{
    /**
     * @var null|Module
     */
    private $_module = null;

    /**
     * @return null|Module
     * @throws \Exception
     */
    protected function getModule()
    {
        if ($this->_module == null) {
            $this->_module = \Yii::$app->getModule(Module::getModuleName());
        }

        if (!$this->_module) {
            throw new \Exception(Module::t('amosattachments', "socialwall module not found, may be you didn't add it to your config?"));
        }

        return $this->_module;
    }
}
