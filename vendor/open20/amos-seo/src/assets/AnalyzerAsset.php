<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle\assets
 * @category   CategoryName
 */

namespace open20\amos\seo\assets;

use yii\web\AssetBundle;

/**
 * Class SeoAsset
 * @package open20\amos\seo\assets
 */
class AnalyzerAsset extends AssetBundle {

    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-seo/src/assets/web';
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];

    /**
     * @inheritdoc
     */
    public $css = [
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/yoast.js',
        'js/yoast-worker.js',
        //'js/yoast-vendor.js'
    ];

    public function init() {
        $this->js = [
            'js/yoast.js',
            'js/yoast-worker.js',
            //'js/yoast-vendor.js'
        ];
        parent::init();
    }

}
