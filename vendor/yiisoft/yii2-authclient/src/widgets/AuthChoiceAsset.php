<?php
/**
 */

namespace yii\authclient\widgets;

use yii\web\AssetBundle;

/**
 * AuthChoiceAsset is an asset bundle for [[AuthChoice]] widget.
 *
 *
 * @since 2.0
 */
class AuthChoiceAsset extends AssetBundle
{
    public $sourcePath = '@yii/authclient/assets';
    public $js = [
        'authchoice.js',
    ];
    public $depends = [
        'yii\authclient\widgets\AuthChoiceStyleAsset',
        'yii\web\YiiAsset',
    ];
}
