<?php

namespace lajax\translatemanager\bundles;

use yii\web\AssetBundle;

/**
 * Contains css files necessary for modify translations on the live site (frontend translation).
 *
 *
 * @since 1.2
 */
class FrontendTranslationAsset extends AssetBundle
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
        'stylesheets/frontend-translation.css',
    ];
}
