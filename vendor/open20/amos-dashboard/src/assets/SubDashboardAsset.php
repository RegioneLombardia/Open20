<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard
 * @category   CategoryName
 */

namespace open20\amos\dashboard\assets;

use yii\web\AssetBundle;
use open20\amos\core\widget\WidgetAbstract;

class SubDashboardAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open20/amos-dashboard/src/assets/web';
    public $css = [
        'less/sub-dashboard.less'
    ];
    public $js = [
        'js/sub-dashboard.js'
    ];
    public $depends = [
    ];

    public function init()
    {

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->js = [];
            $this->depends = ['open20\amos\dashboard\assets\DashboardFullsizeAsset'];
        } else {
            $moduleL = \Yii::$app->getModule('layout');
            if (!empty($moduleL)) {
                $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
                $this->depends [] = 'open20\amos\layout\assets\IsotopeAsset';
            } else {
                $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
                $this->depends [] = 'open20\amos\core\views\assets\IsotopeAsset';
            }
        }

        parent::init();
    }
}