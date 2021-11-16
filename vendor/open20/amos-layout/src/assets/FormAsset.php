<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

namespace open20\amos\layout\assets;

use yii\web\AssetBundle;

class FormAsset extends AssetBundle
{
    public $css = [
        // TODO MOVE FROM BASE ASSET
        //'less/form.less',
    ];
    public $js = [
        'js/form.js',      
    ];
    public $depends = [
        //'open20\amos\layout\assets\BaseAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/resources/form';

        parent::init();
    }
}
