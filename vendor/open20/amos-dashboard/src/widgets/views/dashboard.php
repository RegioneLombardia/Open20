<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard
 * @category   CategoryName
 */
/** @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard * */

/** @var \yii\web\View $this * */
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\assets\AmosCoreAsset;
use open20\amos\dashboard\assets\ModuleDashboardAsset;
use open20\amos\emailmanager\AmosEmail;
use yii\helpers\Html;
use open20\amos\dashboard\AmosDashboard;
use open20\amos\core\utilities\ModalUtility;
use yii\widgets\Pjax;

AmosCoreAsset::register($this);
ModuleDashboardAsset::register($this);
AmosIcons::map($this);

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<input type="hidden" id="saveDashboardUrl" value="<?= Yii::$app->urlManager->createUrl(['dashboard/manager/save-dashboard-order']); ?>"/>
<input type="hidden" id="currentDashboardId" value="<?= $currentDashboard['id'] ?>"/>

<?php
//DA SOSTITUIRE
?>
<div id="dashboard-edit-toolbar" class="hidden">
    <?=
    Html::a(
        AmosDashboard::t('amosemail', 'Salva'),
        'javascript:void(0);',
        [
            'id' => 'dashboard-save-button',
            'class' => 'btn btn-success bk-saveOrder',
        ]
    );
    ?>

    <?=
    Html::a(
        AmosDashboard::t('amosemail', 'Annulla'),
        \yii\helpers\Url::current(),
        [
            'class' => 'btn btn-danger bk-saveDelete',
        ]
    );
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
        <ul id="widgets-icon" class="bk-sortableIcon plugin-list" role="menu">
            <?php
            //indice di questa dashboard
            $thisDashboardIndex = 'dashboard_' . $currentDashboard->slide;

            //recupera i widgets di questa dashboard
            $thisDashboardWidgets = $currentDashboard->getAmosWidgetsSelectedIcon($forceAll, $widgetParentClass)->all();
            if ($thisDashboardWidgets && count($thisDashboardWidgets) > 0) {
                foreach ($thisDashboardWidgets as $widget) {
                    $widgetObj = Yii::createObject($widget['classname']);
                    echo $widgetObj::widget();
                }
            } else {
                AmosDashboard::tHtml('amosadmin', 'Non ci sono widgets selezionati per questa dashboard');
            }
            ?>
        </ul>
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
                $thisDashboardGraphicWidgets = $currentDashboard->amosWidgetsSelectedGraphic;
                $showWidgets = [];

                if (is_array($thisDashboardGraphicWidgets) && count($thisDashboardGraphicWidgets) > 0) {
                    if ($graphicCustomSize) {
                        foreach ($thisDashboardGraphicWidgets as $value) {
                            $widgetGraphic = Yii::createObject($value->classname);
                            if (empty($widgetGraphic->classFullSize)) {
                                array_unshift($showWidgets, $widgetGraphic);
                            } else {
                                $showWidgets[] = $widgetGraphic;
                            }
                        }

                        if (count($showWidgets) > 0) {
                            foreach ($showWidgets as $widgetGraphic2) {
                                $widgetObjG = Yii::createObject($widgetGraphic2['classname']);
                                ?>
                                <div <?=
                (($layoutModuleSet) ? '' : 'class="' . $classDivGraphic) . (($graphicCustomSize === true && !empty($widgetObjG->classFullSize)) ? $widgetObjG->classFullSize : '') . '"'
                                ?>  data-code="<?= $widgetObjG::classname() ?>" data-module-name="<?= $widgetObjG->moduleName ?>"><?= $widgetObjG::widget(); ?>
                                </div>
                                <?php
                            }
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DASHBOARD 2 LEVEL -->
<?= ModalUtility::amosModal([
    'id' => 'modal-2liv-dashboard',
    'containerOptions' => ['class' => 'modal-utility modal-dashboard-2level'],
]);
?>