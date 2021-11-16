<?php
/**
 */

namespace yii\widgets;

use yii\web\AssetBundle;

/**
 * The asset bundle for the [[ActiveForm]] widget.
 *
 * @since 2.0
 */
class ActiveFormAsset extends AssetBundle
{
    public $sourcePath = '@yii/assets';
    public $js = [
        'yii.activeForm.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
