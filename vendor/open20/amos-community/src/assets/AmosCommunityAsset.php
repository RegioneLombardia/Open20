<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\assets;

use yii\web\AssetBundle;
use open20\amos\core\widget\WidgetAbstract;

/**
 * Class AmosCommunityAsset
 * @package open20\amos\community\assets
 */
class AmosCommunityAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open20/amos-community/src/assets/web';

    public $js = [
        'js/community.js'
    ];
    public $css = [
        'less/community.less',
    ];

    public $depends = [
    ];

    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');

        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS){
            $this->css = ['less/community_fullsize.less'];
        }

        if(!empty($moduleL))
        {
            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
            $this->depends [] = 'open20\amos\layout\assets\SpinnerWaitAsset';
        }
        else
        {
            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
            $this->depends [] = 'open20\amos\core\views\assets\SpinnerWaitAsset';
        }
        parent::init();
    }

}