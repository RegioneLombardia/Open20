<?php
/**
 */

namespace yii\authclient\widgets;

use yii\web\AssetBundle;

/**
 * AuthChoiceAsset is an asset bundle for [[AuthChoice]] widget.
 *
 * @since 2.0
 */
class AuthChoiceStyleAsset extends AssetBundle
{
    public $sourcePath = '@yii/authclient/assets';
    public $css = [
        'authchoice.css',
    ];
}