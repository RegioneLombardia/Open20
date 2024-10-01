<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics\views\hierarchical-documents
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\ChangeView;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiAsset;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocuments;
use yii\web\View;
use yii\widgets\Pjax;

ModuleDocumentiAsset::register($this);

/**
 * @var View $this
 * @var WidgetGraphicsHierarchicalDocuments $widget
 * @var yii\data\ActiveDataProvider $dataProviderFolders
 * @var yii\data\ActiveDataProvider $dataProviderDocuments
 * @var string $currentView
 * @var string $toRefreshSectionId
 * @var array $availableViews
 */

?>
<div class="document grid-item grid-item--fullwidth">
<div class="box-widget hierarchical-documents col-xs-12 nop">
    <div class="box-widget-toolbar row nom">
        <div class="col-xs-6 nop">
            <h2 class="box-widget-title"><?= AmosDocumenti::t('amosdocumenti', 'Documenti') ?></h2>
            <?= Html::a(AmosDocumenti::t('amosdocumenti', 'Visualizza Tutti'), ['/documenti'],
                ['class' => 'read-all']); ?>
        </div>
        <!--<div class="row nom btn-tools-container col-xs-6">
            <div class="pull-right">
                <?= ChangeView::widget([
                    'dropdown' => $widget->getCurrentView(),
                    'views' => $widget->getAvailableViews(),
                ]); ?>
            </div>
        </div>-->
    </div>
    <?php Pjax::begin(['id' => $toRefreshSectionId, 'enablePushState' => false, 'timeout' => 10000]); ?>
    <div id="hierarchical-widget-address-bar-id" class="hierarchical-widget-address-bar col-xs-12">
        <?php echo $widget->getNavBar() ?>
    </div>
    <div id="hierarchical-widget-list-id" class="hierarchical-widget-list col-xs-12">
        <?php if ($dataProviderFolders->count > 0) {?>
            <div  class="col-xs-12">
                <h4><strong><?= AmosDocumenti::t('amosdocumenti', 'Folders')?></strong></h4>
                <?= $this->render('data_provider_part', [
                    'widget' => $widget,
                    'dataProvider' => $dataProviderFolders,
                    'currentView' => $currentView,
                ]) ?>
            </div>
        <?php } ?>
        <?php if ($dataProviderDocuments->count > 0) {?>
            <div class="col-xs-12">
            <h4><strong><?= AmosDocumenti::t('amosdocumenti', 'Documents')?> </strong></h4>
            <?= $this->render('data_provider_part', [
                'widget' => $widget,
                'dataProvider' => $dataProviderDocuments,
                'currentView' => $currentView,
                'availableViews' => $availableViews,
            ]) ?>
        </div>
        <?php } ?>

    </div>
    <?php Pjax::end(); ?>
</div>
</div>
