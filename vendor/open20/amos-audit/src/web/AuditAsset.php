<?php

namespace open20\amos\audit\web;

use yii\web\AssetBundle;

/**
 * AuditAsset
 * @package open20\amos\audit\assets
 */
class AuditAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@open20/amos/audit/web/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/audit.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}