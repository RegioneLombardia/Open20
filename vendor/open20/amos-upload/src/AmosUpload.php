<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\upload
 * @category   CategoryName
 */

namespace open20\amos\upload;

use pendalf89\filemanager\Module;

class AmosUpload extends Module
{
    public $controllerNamespace = 'open20\amos\upload\controllers';

    public function init()
    {
        parent::init(
        // initialize the module with the configuration loaded from config.php
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php')));
    }
}
