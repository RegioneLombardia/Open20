<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard
 * @category   CategoryName
 */

namespace open20\amos\dashboard\widgets;

use Yii;
use yii\base\Widget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\AmosDashboard;
use open20\amos\dashboard\models\search\AmosWidgetsSearch;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\dashboard\assets\SubDashboardAsset;
use open20\amos\core\views\assets\AmosCoreAsset;
use open20\amos\dashboard\assets\ModuleDashboardAsset;

/**
 * Class DashboardWidget
 * @package open20\amos\dashboard\widgets
 */
class DashboardWidget extends Widget
{

    /**
     * Title that show in the breadcrumb
     * @var string
     */
    public
        $title,
        $forceAll = true,
        $classDivGraphic = 'grid-item',
        $graphicCustomSize = false,
        $widgetParentClassname = null;

    /**
     * widget initialization
     */
    public function init()
    {
        parent::init();

        if (empty($this->title)) {
            $this->title = AmosDashboard::t('amosdashboard', 'Dashboard del plugin');
        }
    }

    /**
     * @return mixed
     */
    public function run()
    {
        return $this->getHtml();
    }

    /**
     * This method render the widget
     * @param type $icons
     * @param type $graphics
     * @return type
     */
    protected function getHtml()
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (!is_null($moduleCwh)) {
            /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
            // Set new cwh scope
            $moduleCwh->resetCwhScopeInSession();
        }

        $moduleL = \Yii::$app->getModule('layout');
        $layoutModuleSet = isset($moduleL);
        $showWidgetsGraphic = [];
        $controller = \Yii::$app->controller;
        $currentDashboard = $controller->getCurrentDashboard();

        AmosCoreAsset::register($controller->getView());
        ModuleDashboardAsset::register($controller->getView());
        AmosIcons::map($controller->getView());
        SubDashboardAsset::register($controller->getView());

        return $this->render(
                'dashboard',
                [
                    'layoutModuleSet' => $layoutModuleSet,
                    'currentDashboard' => $currentDashboard,
                    'classDivGraphic' => $this->classDivGraphic,
                    'graphicCustomSize' => $this->graphicCustomSize,
                    'forceAll' => $this->forceAll,
                    'title' => $this->title,
                    'widgetParentClass' => $this->widgetParentClassname
                ]
        );
    }

}
