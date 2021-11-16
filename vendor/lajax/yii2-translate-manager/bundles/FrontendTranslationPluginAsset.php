<?php

namespace lajax\translatemanager\bundles;

use yii\web\AssetBundle;

/**
 * Contains javascript files necessary for modify translations on the live site (frontend translation).
 *
 *
 * @since 1.2
 */
class FrontendTranslationPluginAsset extends AssetBundle
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
        'javascripts/frontend-translation.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'lajax\translatemanager\bundles\TranslationPluginAsset',
    ];
}
