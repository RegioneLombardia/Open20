<?php

namespace app\modules\uikit\assets;


class MainAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/modules/uikit/assets';
    /**
     * @inheritdoc
     */
    public $js = [
        'js/zaa.js',
    ];

    /**
     * @inheritdoc
     */
    public $css = [];

    /**
     * @inheritdoc
     */
    public $depends = [
        'luya\admin\assets\Main',
        'app\modules\uikit\assets\TinyMceAsset',
    ];
    
    
}
