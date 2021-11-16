<?php

/**
 */

namespace kv4nt\owlcarousel;

use yii\web\AssetBundle;

/**
 * Asset Bundle for Owl Carousel Widget
 *
 */
class OwlCarouselAsset extends AssetBundle
{

    /**
     * @var string the directory that contains the source asset files for this asset bundle.
     */
    public $sourcePath = '@bower/owl.carousel';

    /**
     * @var array list of CSS files that this bundle contains.
     */
    public $css = [
        'dist/assets/owl.carousel.min.css',
        'dist/assets/owl.theme.default.min.css',
    ];

    /**
     * @var array list of JavaScript files that this bundle contains.
     */
    public $js = [
        'dist/owl.carousel.min.js',
    ];

    /**
     * @var array list of depends assets that this bundle contains.
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
