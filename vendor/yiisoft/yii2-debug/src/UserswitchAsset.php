<?php
/**
 */

namespace yii\debug;

use yii\web\AssetBundle;

/**
 * User switch asset bundle
 *
 * @since 2.0.10
 */
class UserswitchAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $sourcePath = '@yii/debug/assets';
    /**
     * {@inheritdoc}
     */
    public $js = [
        'js/userswitch.js',
    ];
}
