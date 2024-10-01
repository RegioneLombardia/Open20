<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\podcast
 * @category   CategoryName
 */

namespace amos\podcast\assets;

use yii\web\AssetBundle;
use open20\amos\core\widget\WidgetAbstract;

class ModulePodcastAsset extends AssetBundle
{
    public $sourcePath = '@vendor/amos/podcast/src/assets/web';

    public $css = [
        'less/podcast.less',
    ];
    public $js = [
    ];
    public $depends = [
    ];

    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');

        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS){
            $this->css = ['less/podcast.less'];
        }

        if(!empty($moduleL)){
            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
        }else{
            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }
}