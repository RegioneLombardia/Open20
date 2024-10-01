<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\video\assets
 * @category   CategoryName
 */

namespace open20\video\assets;

use yii\web\AssetBundle;
use open20\amos\core\widget\WidgetAbstract;

class ModuleVideoAsset extends AssetBundle
{
    /**
     * @var type
     */
    public $sourcePath = '@vendor/open20/amos-video/src/assets/web';

    /**
     * @var type
     */
    public $css = [
        'less/video_design_bi.less'
    ];
    
    /**
     * @var type
     */
    public $js = [

    ];
    
    /**
     * @var type
     */
    public $depends = [];
    /**
     * 
     */
    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');

        if (!empty($moduleL)) {
            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
        } else {
            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }
}