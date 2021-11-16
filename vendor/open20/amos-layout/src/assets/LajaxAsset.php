<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-layout
 * @category   CategoryName
 */

namespace open20\amos\layout\assets;

use yii\web\AssetBundle;

class LajaxAsset extends AssetBundle
{
    public $sourcePath = '@vendor/lajax/yii2-translate-manager/assets';

    public $css        = [
    ];

    public $js         = [
        'javascripts/lajax.js',
        'javascripts/translate.js',
        'javascripts/language.js',
        'javascripts/scan.js',
        'javascripts/helpers.js',
        'javascripts/md5.js',
        'javascripts/frontend-translation.js',
    ];
    
    public $depends    = [
    ];

}