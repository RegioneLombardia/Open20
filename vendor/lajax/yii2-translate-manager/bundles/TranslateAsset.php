<?php

namespace lajax\translatemanager\bundles;

use yii\web\AssetBundle;

/**
 * Contains css files necessary for modify translations on the backend.
 *
 *
 * @since 1.0
 */
class TranslateAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@lajax/translatemanager/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'stylesheets/helpers.css',
        'stylesheets/translate.css',
    ];
}
