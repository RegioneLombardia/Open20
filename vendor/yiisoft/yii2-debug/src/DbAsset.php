<?php
/**
 */

namespace yii\debug;

use yii\web\AssetBundle;

/**
 * DB asset bundle
 *
 * @since 2.1.0
 */
class DbAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $sourcePath = '@yii/debug/assets';
    /**
     * {@inheritdoc}
     */
    public $js = [
        'js/db.js',
    ];
}
