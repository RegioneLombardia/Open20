<?php

use yii\widgets\ListView;
use open20\amos\core\module\BaseAmosModule;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignUtility;
use open20\amos\events\AmosEvents;
use yii\helpers\Html;
use Yii;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$isGuest = \Yii::$app->user->isGuest;
?>

<?php
$modelLabel = strtolower($model->getGrammar()->getModelLabel());
$hideBiPluginHeader = (strpos($cssClass, 'hide-bi-plugin-header') !== false) ? true : false;
$hideModuleBackendIfEmptyList = (strpos($cssClass, 'hide-module-if-empty-list') !== false && $dataProvider->getTotalCount() <= 0) ? true : false;

$classModuloBackend = 'modulo-backend-' . $modelLabel . ' ' . $cssClass;
if ($hideModuleBackendIfEmptyList) {
    $classModuloBackend .= ' ' . 'd-none';
}

?>
<div class="<?= $classModuloBackend ?>">
    <?php if (!$hideBiPluginHeader) : ?>
        <?php
        if ($isGuest) {
            $titleSection = AmosEvents::t('amosevents', 'Eventi');
            $urlLinkAll = '/events/event/all-events';
            $labelLinkAll = AmosEvents::t('amosevents', 'Tutti gli eventi');
            $titleLinkAll = AmosEvents::t('amosevents', 'Visualizza la lista degli eventi');
        } else {
            $titleSection = AmosEvents::t('amosevents', 'Eventi di mio interesse');
            $urlLinkAll = '/events/event/own-interest';
            $labelLinkAll = AmosEvents::t('amosevents', 'Tutti gli eventi di mio interesse');
            $titleLinkAll = AmosEvents::t('amosevents', 'Visualizza la lista degli eventi di mio interesse');
        }

        $labelCreate = AmosEvents::t('amosevents', 'Nuovo');
        $titleCreate = AmosEvents::t('amosevent', 'Crea una nuovo evento');
        $labelManage = AmosEvents::t('amosevents', 'Gestisci');
        $titleManage = AmosEvents::t('amosevents', 'Gestisci gli eventi');
        $urlCreate = '/events/event/create';

        $manageLinks = [];
        $controller = \open20\amos\events\controllers\EventController::class;
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

    <div class="card-<?= $modelLabel ?>-container">
        <?php
        if ($dataProvider->getTotalCount() > 0) {

            $listViewLayout = $this->render('@vendor/open20/design/src/components/yii2/views/listViewLayout');

            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_itemCardEventsDesign',
                'viewParams' => [
                    'detailPage' => $detailPage,
                    'viewFields' => $viewFields,
                    'blockItemId' => $blockItemId,
                ],
                'layout' => $listViewLayout,
                'summary' => DesignUtility::getLayoutSummary($withPagination),
                'pager' => DesignUtility::listViewPagerConfig(),
                'itemOptions' => [
                    'tag' => false
                ],
                'options' => [
                    'class' => 'list-view w-100'
                ]
            ]);
        } else { ?>
            <?php if (!$isGuest) : ?>
                <div class="no-<?= str_replace(' ', '-', $modelLabel) ?>-alert">
                    <div class="alert alert-warning" role="alert">
                        <p class="mb-0"><strong><?= AmosEvents::t('amosevents', 'Non ci sono eventi di tuo interesse!') ?></strong></p>
                        <?=
                        Html::a(
                            AmosEvents::t('amosevents', 'Clicca qui'),
                            '/events/event/all-events',
                            [
                                'title' => AmosEvents::t('amosevents', 'Clicca e scopri tutti gli eventi della piattaforma {platformName}', ['platformName' => \Yii::$app->name]),
                                'class' => 'btn btn-xs btn-primary'
                            ]
                        )
                            . ' ' .
                            AmosEvents::t('amosevents', 'e scopri ora tutti gli eventi di {platformName}', ['platformName' => \Yii::$app->name])
                        ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="no-<?= str_replace(' ', '-', $modelLabel) ?>-alert">
                    <div class="alert alert-warning" role="alert">
                        <p class="mb-0"><strong><?= AmosEvents::t('amosevents', 'Non sono presenti eventi') ?></strong></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php } ?>
    </div>
</div>