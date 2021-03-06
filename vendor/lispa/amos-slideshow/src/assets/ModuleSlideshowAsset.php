<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\slideshow
 * @category   CategoryName
 */

namespace lispa\amos\slideshow\assets;

use yii\web\AssetBundle;

/**
 * Class ModuleSlideshowAsset
 * @package lispa\amos\slideshow\assets
 */
class ModuleSlideshowAsset extends AssetBundle
{
    public $sourcePath = '@vendor/lispa/amos-slideshow/src/assets/web';

    public $css = [
        'less/slideshow.less'
    ];
    public $js = [        
    ];
    public $depends = [
    ];

    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');
        if(!empty($moduleL))
        { $this->depends [] = 'lispa\amos\layout\assets\BaseAsset'; }
        else
        { $this->depends [] = 'lispa\amos\core\views\assets\AmosCoreAsset'; }
        parent::init(); // TODO: Change the autogenerated stub
    }
}
