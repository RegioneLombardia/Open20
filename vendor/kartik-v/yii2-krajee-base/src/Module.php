<?php

/**
 * @package   yii2-krajee-base
 * @version   1.9.9
 */

namespace kartik\base;

use ReflectionException;
use yii\base\InvalidConfigException;
use yii\base\Module as YiiModule;

/**
 * Base module class for Krajee extensions
 *
 * @since 1.9.9
 */
class Module extends YiiModule implements BootstrapInterface
{
    use TranslationTrait;
    use BootstrapTrait;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function init()
    {
        $this->initBsVersion();
        parent::init();
        $this->initI18N();
    }
}
