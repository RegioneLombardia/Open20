<?php

namespace mdm\admin;

use yii\web\AssetBundle;

/**
 * Description of AnimateAsset
 *
 * @since 2.5
 */
class AnimateAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@mdm/admin/assets';
    /**
     * @inheritdoc
     */
    public $css = [
        'animate.css',
    ];

}
