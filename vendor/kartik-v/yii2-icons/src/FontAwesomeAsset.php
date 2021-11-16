<?php

/**
 * @package yii2-icons
 * @version 1.4.7
 */

namespace kartik\icons;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Asset bundle for FontAwesome SVG/JS icon set. Uses SVG/JS client assets from font-awesome CDN repository.
 *
 *
 * @since 1.0
 */
class FontAwesomeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $js = [
        // font awesome free version (can be overridden from yii2 asset manager)
        'https://use.fontawesome.com/releases/v5.15.1/js/all.js'
    ];

    /**
     * @inheritdoc
     */
    public $jsOptions = [
        'position' => View::POS_HEAD,
        'defer' => true,
        'crossorigin' => 'anonymous'
    ];
}
