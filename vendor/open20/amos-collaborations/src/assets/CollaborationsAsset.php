<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\assets
 * @category   CategoryName
 */

namespace open20\amos\collaborations\assets;

use yii\web\AssetBundle;
use open20\amos\core\widget\WidgetAbstract;

/**
 * Class collaborationsAsset
 * @package open20\amos\collaborations\assets
 */
class CollaborationsAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-collaborations/src/assets/web';
    
    /**
     * @inheritdoc
     */
    public $js = [
    ];
    
    /**
     * @inheritdoc
     */
    public $css = [
       
    ];
    
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset'
    ];

    public function init()
    {
        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS){
            $this->css = ['less/collaborations-bi.less'];
        }

        parent::init();
    }
}