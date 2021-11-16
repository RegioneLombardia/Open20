<?php
/**
 */

namespace yii\debug;

use yii\web\AssetBundle;

/**
 * Userswitch asset bundle
 *
 * @since 2.0.10
 */
class UserswitchAsset extends AssetBundle
{
    public $sourcePath = '@yii/debug/assets';
    public $js = [
        'userswitch.js',
    ];
}