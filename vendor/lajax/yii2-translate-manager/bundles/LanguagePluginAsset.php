<?php

namespace lajax\translatemanager\bundles;

use yii\web\AssetBundle;

/**
 * Contains javascript files necessary for language list on the backend.
 *
 *
 * @since 1.0
 */
class LanguagePluginAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@lajax/translatemanager/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'javascripts/helpers.js',
        'javascripts/language.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
