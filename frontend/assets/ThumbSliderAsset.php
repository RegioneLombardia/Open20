<?php

namespace app\assets;

/**
 * Application Asset File.
 */
class ThumbSliderAsset extends \luya\web\Asset
{
    public $sourcePath = '@app/modules/uikit/assets';

    public $css = [
        'css/lightslider.css',
        'css/thumbslider.css',
        'css/retroSliderHome.css',
    ];

    public $js = [
        'js/base64.js',
        'js/lightslider.js',
        'js/lightslideranimation.js',
       // 'js/script.js'
    ];

    public $depends = [
        'open20\design\assets\BootstrapItaliaDesignAsset' //do not remove
    ];
}
