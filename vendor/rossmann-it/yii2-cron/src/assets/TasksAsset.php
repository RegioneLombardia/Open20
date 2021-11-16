<?php
/**
 * @since 06.02.2016
 */
namespace rossmann\cron\assets;

use yii\web\AssetBundle;

class TasksAsset extends AssetBundle
{
    public $sourcePath = '@vendor/rossmann-it/yii2-cron/src/assets';

    public $js = [
        'manager_actions.js',
    ];
}
