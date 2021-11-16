<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout
 * @category   CategoryName
 */

namespace open20\amos\layout\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package open20\amos\layout\assets
 */

class MasonryAsset extends AssetBundle {

    public $js = [
        'js/masonry.pkgd.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/resources/masonry';

        parent::init();
    }

}
