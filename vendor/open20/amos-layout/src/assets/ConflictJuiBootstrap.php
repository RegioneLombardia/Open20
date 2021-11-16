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

class ConflictJuiBootstrap extends AssetBundle {

    public $sourcePath = '@vendor/open20/amos-core/views/assets/web';
    
    public $js = [
        'js/conflictJuiBootstrap.js',
    ];
    
    public $depends = [
        'yii\jui\JuiAsset'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/resources/base';

        parent::init();
    }

}
