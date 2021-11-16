<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

/**
 * @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard
 * @var \yii\web\View $this
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\layout\assets\BaseAsset;
use open20\amos\dashboard\AmosDashboard;
use open20\amos\dashboard\assets\ModuleDashboardAsset;
use open20\amos\news\AmosNews;
use yii\helpers\Html;

BaseAsset::register($this);

ModuleDashboardAsset::register($this);

AmosIcons::map($this);

$this->title = $this->context->module->name;

$this->params['breadcrumbs'][] = ['label' => $this->title];
?>


<input type="hidden" id="saveDashboardUrl"
       value="<?= Yii::$app->urlManager->createUrl(['dashboard/manager/save-dashboard-order']); ?>"/>
<input type="hidden" id="currentDashboardId" value="<?= $currentDashboard['id'] ?>"/>

<div id="dashboard-edit-toolbar" class="hidden">
    <?=
    Html::a(AmosNews::t('amosnews', 'Salva'), 'javascript:void(0);', [
        'id' => 'dashboard-save-button',
        'class' => 'btn btn-success bk-saveOrder',
    ]);
    ?>

    <?=
    Html::a(AmosNews::t('amosnews', 'Annulla'), \yii\helpers\Url::current(), [
        'class' => 'btn btn-danger bk-saveDelete',
    ]);
    ?>

</div>

<?php
/*
 * @$widgetsIcon elenco dei plugin ad icona
 * @$widgetsGrafich elenco dei plugin ad grafici
 * @$dashboardsNumber numero delle dashboard da mostrare
 */
?>

<nav data-dashboard-index="<?= $currentDashboard->slide ?>">

    <div class="actions-dashboard-container">
        <ul id="widgets-icon" class="bk-sortableIcon plugin-list"
            role="menu">
            <?php
            //indice di questa dashboard
            $thisDashboardIndex = 'dashboard_' . $currentDashboard->slide;

            //recupera i widgets di questa dashboard
            $thisDashboardWidgets = $currentDashboard->amosWidgetsSelectedIcon;

            if ($thisDashboardWidgets && count($thisDashboardWidgets) > 0) {

                foreach ($thisDashboardWidgets as $widget) {
                    $widgetObj = Yii::createObject($widget['classname']);
                    echo $widgetObj::widget();
                }
            } else {
                AmosDashboard::t('amosnews', 'Non ci sono widgets selezionati per questa dashboard');
            }
            ?>
        </ul>
    </div>

</nav>








