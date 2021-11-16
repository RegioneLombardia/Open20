<?php
/**
 */

namespace yii\grid;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files for the [[GridView]] widget.
 *
 * @since 2.0
 */
class GridViewAsset extends AssetBundle
{
    public $sourcePath = '@yii/assets';
    public $js = [
        'yii.gridView.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
