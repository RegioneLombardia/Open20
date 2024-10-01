<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\assets
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\assets;

use yii\web\AssetBundle;
use open20\amos\core\widget\WidgetAbstract;

/**
 * Class PartnershipProfilesAsset
 * @package open20\amos\partnershipprofiles\assets
 */
class PartnershipProfilesAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-partnership-profiles/src/assets/web';
    
    /**
     * @inheritdoc
     */
    public $js = [
    ];
    
    /**
     * @inheritdoc
     */
    public $css = [
        'less/partnership-profiles.less',
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
            $this->css = ['less/partnership-profiles_fullsize.less','less/partnership-profiles-bi.less'];
        }

        parent::init();
    }
}