<?php

namespace kop\y2sp\assets;

use yii\web\AssetBundle;

/**
 * This is "InfiniteAjaxScrollAsset" class.
 *
 * This class is an asset bundle for {@link http://infiniteajaxscroll.com/ JQuery Infinite Ajax Scroll plugin}.
 *
 *
 */
class InfiniteAjaxScrollAsset extends AssetBundle
{

    public $sourcePath = '@vendor/webcreate/jquery-ias/src';

    public $js = [
        'callbacks.js',
        'jquery-ias.js'
    ];

    /**
     * @var array List of bundle class names that this bundle depends on.
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    
}
