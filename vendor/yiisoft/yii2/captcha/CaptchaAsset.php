<?php
/**
 */

namespace yii\captcha;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files needed for the [[Captcha]] widget.
 *
 * @since 2.0
 */
class CaptchaAsset extends AssetBundle
{
    public $sourcePath = '@yii/assets';
    public $js = [
        'yii.captcha.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
