<?php
/** @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard * */

/** @var \yii\web\View $this * */
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\AmosDashboard;
use open20\amos\dashboard\assets\ModuleDashboardAsset;
use open20\amos\core\utilities\ModalUtility;
use yii\helpers\Html;
use yii\widgets\Pjax;

ModuleDashboardAsset::register($this);
AmosIcons::map($this);
$moduleL = \Yii::$app->getModule('layout');
$layoutModuleSet = isset($moduleL);

$this->title = $this->context->module->name;
?>

<input type="hidden" id="saveDashboardUrl" value="<?= Yii::$app->urlManager->createUrl(['dashboard/manager/save-dashboard-order']); ?>"/>
<input type="hidden" id="currentDashboardId" value="<?= $currentDashboard['id'] ?>"/>

<div id="dashboard-edit-toolbar" class="hidden pull-right">
<?=
  Html::a(AmosDashboard::tHtml('amosdashboard', 'Salva'), 'javascript:void(0);', [
    'id' => 'dashboard-save-button',
    'class' => 'btn btn-success bk-saveOrder',
    'title' => AmosDashboard::t('amosdashboard', 'Salva ordinamento dashboard')
  ]);
?>

<?=
  Html::a(AmosDashboard::tHtml('amosdashboard', 'Annulla'), \yii\helpers\Url::current(), [
    'class' => 'btn btn-danger bk-saveDelete',
    'title' => AmosDashboard::t('amosdashboard', 'Annulla ordinamento dashboard')
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
<!-- ICONS PLUGINS -->
<nav data-dashboard-index="<?= $currentDashboard->slide ?>">
  <div class="actions-dashboard-container clearfixplus">
    <?php Pjax::begin(['id' => 'widget-icons-pjax-block']); ?>
    <div id="widgets-icon" class="bk-sortableIcon plugin-list" role="menu">
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
        AmosDashboard::tHtml('amosdashboard', 'Non ci sono widgets selezionati per questa dashboard');
      }
    ?>
    </div>
    <?php Pjax::end() ?>
  </div>
</nav>

<!-- WIDGET GRAFICI -->
<div id="bk-pluginGrafici">
  <div class="graphics-dashboard-container">
    <div id="widgets-graphic" class="widgets-graphic-sortable">
      <div class="grid">
        <div class="grid-sizer"></div>
        <?php
        //recupera i widgets di questa dashboard
        $thisDashboardWidgets = $currentDashboard->amosWidgetsSelectedGraphic;
        if ($thisDashboardWidgets && count($thisDashboardWidgets) > 0) {
          foreach ($thisDashboardWidgets as $widget) {
            $widgetObj = Yii::createObject($widget['classname']);
        ?>
          <div <?= (($layoutModuleSet) ? '' : 'class="grid-item' . ' ' . $widgetObj->classFullSize . '"') ?>
            data-code="<?= $widgetObj::classname() ?>"
            data-module-name="<?= $widgetObj->moduleName ?>"><?= $widgetObj::widget(); ?></div>
          <?php
          }
        }
        ?>
      </div>
    </div>
  </div>
</div>

<!-- MODAL DASHBOARD 2 LEVEL -->
<?=
ModalUtility::amosModal([
  'id' => 'modal-2liv-dashboard',
  'containerOptions' => ['class' => 'modal-utility modal-dashboard-2level'],
]);
?>