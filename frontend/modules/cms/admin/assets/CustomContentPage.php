<?php

namespace app\modules\cms\admin\assets;

/**
 * CMS Main Asset Bundle.
 *
 * @since 1.0.0
 */
class CustomContentPage extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@app/modules/cms/admin/resources';

    /**
     * @inheritdoc
     */
    public $js = [
        
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'scss/main.scss',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'luya\admin\assets\Main',
        'open20\amos\layout\assets\FontAsset', //fix temp font

    ];
    
    /**
     * @inheritdoc
     */
    public $publishOptions = [
        
    ];
}
