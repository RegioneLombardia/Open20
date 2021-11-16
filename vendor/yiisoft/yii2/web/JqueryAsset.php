<?php
/**
 */

namespace yii\web;

/**
 * This asset bundle provides the [jQuery](http://jquery.com/) JavaScript library.
 *
 * @since 2.0
 */
class JqueryAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery/dist';
    public $js = [
        'jquery.js',
    ];
}
