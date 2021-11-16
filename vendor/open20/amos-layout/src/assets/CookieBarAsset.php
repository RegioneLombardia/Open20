<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout
 * @category   CategoryName
 */

namespace open20\amos\layout\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package open20\amos\layout\assets
 */
class CookieBarAsset extends AssetBundle
{
    public $js = [
        'js/cookiebar.js',
    ];

    public $css = [
    ];

    public $sourcePath = '@vendor/open20/amos-layout/src/assets/resources/base';

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
