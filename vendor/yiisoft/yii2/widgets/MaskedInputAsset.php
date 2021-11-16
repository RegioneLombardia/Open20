<?php
/**
 */

namespace yii\widgets;

use yii\web\AssetBundle;

/**
 * The asset bundle for the [[MaskedInput]] widget.
 *
 * Includes client assets of [jQuery input mask plugin](https://github.com/RobinHerbots/Inputmask).
 *
 * @since 2.0
 */
class MaskedInputAsset extends AssetBundle
{
    public $sourcePath = '@bower/inputmask/dist';
    public $js = [
        'jquery.inputmask.bundle.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
