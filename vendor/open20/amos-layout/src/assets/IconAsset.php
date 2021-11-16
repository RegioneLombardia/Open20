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

class IconAsset extends AssetBundle
{
    public $css = [
        'icon-dash/style.css',          //genereted by icon-moon
        'icon-am/style.css',            //genereted by icon-moon
        'icon-flag/css/flag-icon.css',  //http://flag-icon-css.lip.is
        'icon-ic/style.css',  //genereted by icon-moon
        'materialdesignicons/css/materialdesignicons.min.css',
    ];
    
    public $js = [
    ];

    public $depends = [
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/resources/icons';

        parent::init();
    }
}
