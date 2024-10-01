<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\videoconference
 * @category   CategoryName
 */

/** @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard * */

/** @var \yii\web\View $this * */
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\assets\AmosCoreAsset;
use open20\amos\dashboard\assets\ModuleDashboardAsset;
use yii\helpers\Html;
use open20\amos\videoconference\AmosVideoconference;

AmosCoreAsset::register($this);
ModuleDashboardAsset::register($this);
AmosIcons::map($this);

$this->title = $this->context->module->name;
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<input type="hidden" id="saveDashboardUrl" value="<?= Yii::$app->urlManager->createUrl(['dashboard/manager/save-dashboard-order']); ?>"/>
<input type="hidden" id="currentDashboardId" value="<?= $currentDashboard['id'] ?>"/>
<?php
//DA SOSTITUIRE
?>
<div id="dashboard-edit-toolbar" class="hidden">
    <?=
    Html::a(AmosVideoconference::t('amosvideoconference', 'Salva'), 'javascript:void(0);', [
        'id' => 'dashboard-save-button',
        'class' => 'btn btn-success bk-saveOrder',
    ]);
    ?>

    <?=
    Html::a(AmosVideoconference::t('amosvideoconference', 'Annulla'), \yii\helpers\Url::current(), [
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
                AmosVideoconference::tHtml('amosvideoconference', 'Non ci sono widgets selezionati per questa dashboard');
            }
            ?>
        </ul>
        
        
       
    </div>

</nav>






