<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiAsset;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti;
use yii\data\ActiveDataProvider;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $listaDocumenti
 * @var WidgetGraphicsUltimiDocumenti $widget
 * @var string $toRefreshSectionId
 */


/** @var AmosDocumenti $moduleDocumenti */
$moduleDocumenti  = \Yii::$app->getModule(AmosDocumenti::getModuleName());
$listaModels = $listaDocumenti->getModels();
ModuleDocumentiAsset::register($this);

?>
<?php
$modelLabel = 'documenti';

$urlLinkAll = '/documenti/documenti/all-documents';
$labelLinkAll = AmosDocumenti::t('amosdocumenti', 'Tutti i documenti');
$titleLinkAll = AmosDocumenti::t('amosdocumenti', 'Visualizza la lista dei documenti');

$labelCreate = AmosDocumenti::t('amosdocumenti', 'Nuovo');
$titleCreate = AmosDocumenti::t('amosdocumenti', 'Crea un nuovo documento');
$labelManage = AmosDocumenti::t('amosdocumenti', 'Gestisci');
$titleManage = AmosDocumenti::t('amosdocumenti', 'Gestisci i documenti');
$urlCreate = '/documenti/documenti/create';

$manageLinks = [];
$controller = \open20\amos\documenti\controllers\DocumentiController::class;
if (method_exists($controller, 'getManageLinks')) {
    $manageLinks = $controller::getManageLinks();
}


$moduleCwh = \Yii::$app->getModule('cwh');
if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
    $scope = $moduleCwh->getCwhScope();
    $isSetScope = (!empty($scope)) ? true : false;
}

?>
<div class="widget-graphic-cms-bi-less card-<?= $modelLabel ?> container">
    <div class="page-header">
    <?= $this->render(
            "@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/bi-less-plugin-header",
            [
                'isGuest' => \Yii::$app->user->isGuest,
                'isSetScope' => $isSetScope,
                'modelLabel' => 'documenti',
                'titleSection' => $widget->getLabel(),
                'urlLinkAll' => $urlLinkAll,
                'labelLinkAll' => $labelLinkAll,
                'titleLinkAll' => $titleLinkAll,
                'labelCreate' => $labelCreate,
                'titleCreate' => $titleCreate,
                'labelManage' => $labelManage,
                'titleManage' => $titleManage,
                'hideCreate' => $moduleDocumenti->hideNewButtonInWGCmsUltimiDocumenti,
                'urlCreate' => $urlCreate,
                'manageLinks' => $manageLinks,
                'canCreate' => \Yii::$app->user->can('DOCUMENTI_CREATE', ['model' => AmosDocumenti::instance()->createModel('Documenti')]),
            ]
        );
        ?>
    </div>

    <div class="list-view">
        <div>
            <div class="" data-role="list-view">
                <?php foreach ($listaModels as $singolodocumento) : ?>
                    <div>
                        <?php if($singolodocumento->is_folder){ ?>
                        <?= $this->render("@vendor/open20/amos-documenti/src/views/documenti/_item_folder", ['model' => $singolodocumento]); ?>
                        <?php }else{ ?>
                            <?= $this->render("@vendor/open20/amos-documenti/src/views/documenti/_item_document", ['model' => $singolodocumento]); ?>
                        <?php }?>

                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>