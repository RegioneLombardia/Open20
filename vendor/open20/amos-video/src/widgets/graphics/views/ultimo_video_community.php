<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\modules\formazione\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;
use open20\video\Module;

/**
 * @var View $this
 * @var ActiveDataProvider $lista
 * @var WidgetGraphicFormazione $widget
 * @var string $toRefreshSectionId
 */

$moduleFormazione = \Yii::$app->getModule(Module::getModuleName());
$listaModels = [$video];

$modelLabel = 'Video';

$labelLinkAll = Module::t('amosvideo', '#allVideo');
$urlLinkAll = '/video/video/all';
$titleLinkAll = Module::t('amosvideo', '#allVideo');

$subTitleSection = Html::tag('p', Module::t('amosvideo', ''));

$urlCreate ='/video/video/create';
$labelCreate =Module::t('amosformazione', 'Nuovo');
$titleCreate = Module::t('amosformazione', 'Crea una nuova unità didattica');

$hideCreate = !\Yii::$app->user->can('VIDEO_ADMIN');


?>


<div class="formazione-background">
    <div class="widget-graphic-cms-bi-less card-<?= $modelLabel ?> container">
        <div class="page-header">
            <?= $this->render(
                "@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/bi-less-plugin-header",
                [
                    'isGuest' => \Yii::$app->user->isGuest,
                    //'isSetScope' => $isSetScope,
                    'modelLabel' => 'Video',
                    'titleSection' => $widget->getLabel(),
                    'subTitleSection' => $subTitleSection,
                    'urlLinkAll' => $urlLinkAll,
                    'labelLinkAll' => $labelLinkAll,
                    'titleLinkAll' => $titleLinkAll,
                    'hideCreate' => $hideCreate,
                    'labelCreate' => $labelCreate,
                    'titleCreate' => $titleCreate,
                    'urlCreate' => $urlCreate,
                    'canCreate' => \Yii::$app->user->can('VIDEO_ADMIN')
                ]
            );
            ?>
        </div>
    </div>
    <?php if (count($video) == 0): ?>
        <div class="container list-items list-empty">
        </div>
    <?php else: ?>
        <div class="container">

                <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>

               
                <div class="flexbox">
                    <?php foreach ($listaModels as $formazione) : /** @var Formazione $formazione */ ?>
                                    <div class="iframe-container" style="flex-grow: 1;">
                                        <h2 class="box-widget-subtitle">
                                            <?php
                                            if (strlen($formazione->title) > 55) {
                                                $stringCut = substr($formazione->title, 0, 55);
                                                $title = substr($stringCut, 0, strrpos($stringCut, ' ')).'... ';
                                            } else {
                                                $title = $formazione->title;
                                            }
                                            echo Html::a($title, ['/video/video/view', 'id' => $formazione->id], ['class' => '']);
                                            ?>
                                        </h2>

                                        <div class="formazione-container">
                                            <?= \amos\sitemanagement\utility\EmbeddedUtility::getUrlEmbeddedVideo($formazione->url, [
                                                'type' => 'iframe',
                                                'hideWatchOnYoutube' => true
                                            ]) ?>
                                        </div>
                                    </div>
                    <?php endforeach; ?>
                </div>
                <?php Pjax::end(); ?>
        </div>
    <?php endif; ?>
        
</div>

