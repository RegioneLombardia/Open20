<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-layout
 * @category   CategoryName
 */

namespace open20\amos\layout\assets;

use yii\web\AssetBundle;

class DialogAsset extends AssetBundle
{
    public $css     = [
    ];
    public $js      = [
        'js/dialog.js',
    ];
    public $depends = [
        'open20\amos\layout\assets\LajaxAsset',
        'kartik\dialog\DialogBootstrapAsset',
        'kartik\dialog\DialogYiiAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__.'/resources/dialog';

        parent::init();
    }
}