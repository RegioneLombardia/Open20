<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\design\layout
 * @category   CategoryName
 */

namespace open20\amos\cwh\assets;

use yii\web\AssetBundle;

class MaterialDesignAsset extends AssetBundle {

    public $sourcePath = '@vendor/open20/amos-cwh/src/assets/web';
    public $js = [
    ];
    public $css = [
        'MaterialDesign-Webfont-master/css/materialdesignicons.min.css',
        'https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init() {
        if (!(isset(\Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader'])) || (isset(\Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader']) && !(\Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader']))) {
            $this->js[] = 'js/header-height.js';
        }
        parent::init();
    }

}
