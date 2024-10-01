<?php

namespace open20\amos\favorites\assets;

use yii\web\AssetBundle;

class AmosFavoriteAsset extends AssetBundle 
{

    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-favorites/src/assets/web';
    
    /**
     * @inheritdoc
     */
    public $css = [
        'less/favorite.less'
    ];
    
    /**
     * @inheritdoc
     */
    public $js = [
    ];
    
    /**
     * @inheritdoc
     */
    public $depends = [
    ];

    /**
     * @inheritdoc
     */
    public function init() {
//        $moduleL = \Yii::$app->getModule('layout');
//        if (!empty($moduleL)) {
//            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
//        } else {
//            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
//        }

        parent::init();
    }

}