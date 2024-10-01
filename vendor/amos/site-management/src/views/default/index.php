<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\views\default
 * @category   CategoryName
 */

use amos\sitemanagement\Module;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\assets\AmosCoreAsset;
use open20\amos\dashboard\assets\ModuleDashboardAsset;

/**
 * @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard
 * @var \yii\web\View $this
 */

echo \open20\amos\dashboard\widgets\DashboardWidget::widget(['widgetParentClassname' =>'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard']);