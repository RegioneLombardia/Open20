<?php
namespace spk\yii2loganalyzer;

use yii\web\AssetBundle;

class LogAnalyzerAssets extends AssetBundle
{
    public $depends = ['yii\web\JqueryAsset'];

    function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        
        if (YII_DEBUG) {
            $this->js[] = 'log.js';
            $this->css[] = 'log.css';
        } else {
            $this->js[] = 'log.min.js';
            $this->css[] = 'log.min.css';
        }
        parent::init();
    }
}