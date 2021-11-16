<?php

namespace lajax\translatemanager\bundles;

use yii\web\AssetBundle;

/**
 * Contains css files necessary for language list on the backend.
 *
 *
 * @since 1.0
 */
class LanguageAsset extends AssetBundle
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
        'stylesheets/language.css',
    ];
}
