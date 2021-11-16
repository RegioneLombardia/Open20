<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\assets
 * @category   CategoryName
 */

namespace open20\amos\myactivities\assets;

use open20\amos\core\widget\WidgetAbstract;
use yii\web\AssetBundle;

/**
 * Class ModuleMyActivitiesAsset
 * @package open20\amos\myactivities\assets
 */
class ModuleMyActivitiesAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-my-activities/src/assets/web';

    /**
     * @inheritdoc
     */
    public $css = [
        'less/my-activities.less'

    ];

    /**
     * @inheritdoc
     */
    public $js = [
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');

        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS){
            $this->css = ['less/my-activities_fullsize.less'];
        }


        if (!empty($moduleL)) {
            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
        } else {
            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }
}
