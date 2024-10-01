<?php

use yii\widgets\ListView;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignUtility;
use yii\helpers\Html;
use Yii;
use open20\amos\collaborations\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
$isGuest = \Yii::$app->user->isGuest;

$modelLabel = strtolower($model->getGrammar()->getModelLabel());
$hideBiPluginHeader = (strpos($cssClass, 'hide-bi-plugin-header') !== false) ? true : false;
$hideModuleBackendIfEmptyList = (strpos($cssClass, 'hide-module-if-empty-list') !== false && $dataProvider->getTotalCount() <= 0) ? true : false;

$classModuloBackend = 'modulo-backend-' . $modelLabel . ' ' . $cssClass;
if ($hideModuleBackendIfEmptyList) {
    $classModuloBackend .= ' ' . 'd-none';
}
?>
<div class="single-list-news">

    <?php if (!$hideBiPluginHeader) : ?>
        <?php
        if ($isGuest) {
            $titleSection = \Yii::t('amoscollaborations', 'Le proposte di collaborazione');
            $urlLinkAll = '/collaborations/collaboration-proposals/index';
            $labelLinkAll = \Yii::t('amoscollaborations','Tutte le proposte');
            $titleLinkAll = \Yii::t('amoscollaborations','Visualizza tutte le proposte');
        } else {
            $titleSection = \Yii::t('amoscollaborations','Le mie proposte di collaborazione');
            $urlLinkAll = '/collaborations/collaboration-proposals/own-interest';
            $labelLinkAll = \Yii::t('amoscollaborations','Tutte le proposte di mio interesse');
            $titleLinkAll = \Yii::t('amoscollaborations','Visualizza la lista delle mie proposte');
        }

        $labelCreate = \Yii::t('amoscollaborations','Nuova');
        $titleCreate = \Yii::t('amoscollaborations','Crea nuova');
        $labelManage = \Yii::t('amoscollaborations','Gestisci');
        $titleManage = \Yii::t('amoscollaborations','Gestisci le proposte');
        $urlCreate = '/collaborations/collaboration-proposals/create';
        $subTitleSection = \Yii::t('amoscollaborations','');

        $manageLinks = [];
        $controller = \open20\amos\collaborations\controllers\CollaborationProposalsController::class;
        if (method_exists($controller, 'getManageLinks')) {
            $manageLinks = $controller::getManageLinks();
        }

        $canCreate = \Yii::$app->user->can('EVENT_CREATE', ['model' => $model]);

        ?>
        <?=
        $this->render(
            '@vendor/open20/design/src/views/layouts/parts/bi-plugin-header',
            [
                'isGuest' => $isGuest,
                'modelLabel' => $modelLabel,
                'titleSection' => $titleSection,
                'subTitleSection' => $subTitleSection,
                'urlLinkAll' => $urlLinkAll,
                'labelLinkAll' => $labelLinkAll,
                'titleLinkAll' => $titleLinkAll,
                'canCreate' => $canCreate,
                'labelCreate' => $labelCreate,
                'titleCreate' => $titleCreate,
                'labelManage' => $labelManage,
                'titleManage' => $titleManage,
                'urlCreate' => $urlCreate,
                'manageLinks' => $manageLinks,
            ]
        );
        ?>
    <?php endif ?>

    <?php
        if ($dataProvider->getTotalCount() > 0) {


            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_itemCollaborations',
                'viewParams' => [
                    'detailPage' => $detailPage,
                    'viewFields' => $viewFields,
                    'blockItemId' => $blockItemId,
                ],
                'summary' => '',
                'itemOptions' => [
                    'tag' => false
                ],
                'options' => [
                    'class' => 'row variable-gutters mt-3'
                ]
            ]);
        } else { ?>
            <div id="no-content-news" class="no-result">
                <p>Non sono presenti contenuti</p>
            </div>
    <?php } ?>
</div>