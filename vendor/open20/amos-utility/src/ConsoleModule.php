<?php

namespace open20\amos\utility;

use yii\helpers\ArrayHelper;

/**
 * Class ConsoleModule
 * @package open20\amos\utility
 */
class ConsoleModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        //Configuration
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'console_config.php');

        //Setup config
        \Yii::configure($this, ArrayHelper::merge($config, $this));

        $this->defaultRoute = 'console';
    }
}
