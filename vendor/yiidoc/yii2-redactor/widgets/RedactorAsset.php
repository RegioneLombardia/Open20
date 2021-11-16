<?php

/**
 */

namespace yii\redactor\widgets;

/**
 * @since 2.0
 */
class RedactorAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/yiidoc/yii2-redactor/assets';
    public $depends = ['yii\web\JqueryAsset'];

    public function init()
    {
        if (YII_DEBUG) {
            $this->js[] = 'redactor.js';
            $this->css[] = 'redactor.css';
        } else {
            $this->js[] = 'redactor.min.js';
            $this->css[] = 'redactor.min.css';
        }
    }

}