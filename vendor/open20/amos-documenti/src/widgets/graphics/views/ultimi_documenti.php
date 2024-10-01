<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiAsset;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View $this
 * @var ActiveDataProvider $listaDocumenti
 * @var WidgetGraphicsUltimiDocumenti $widget
 * @var string $toRefreshSectionId
 */

ModuleDocumentiAsset::register($this);

/** @var AmosDocumenti $moduleDocumenti */
$moduleDocumenti = \Yii::$app->getModule(AmosDocumenti::getModuleName());
$listaDocumenti->query->andWhere(['is_folder' => 0]);
$documents = $listaDocumenti->getModels();

?>

<div class="document grid-item grid-item--width2">
    <div class="box-widget latest-documents">
        <div class="box-widget-toolbar">
            <h1 class="box-widget-title col-xs-10 nop"><?= $widget->widgetTitle ?></h1>
            <?php if (isset($moduleDocumenti) && !$moduleDocumenti->hideWidgetGraphicsActions) { ?>
                <?= WidgetGraphicsActions::widget([
                    'widget' => $widget,
                    'tClassName' => AmosDocumenti::className(),
                    'actionRoute' => '/documenti/documenti/create',
                    'toRefreshSectionId' => $toRefreshSectionId
                ]);
            } ?>
        </div>
        <section>
            <h2 class="sr-only"><?= $widget->getLabel() ?></h2>
            <div>
                <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
                <div role="listbox">
                    <?php if (count($documents) == 0): ?>
                        <?php
                        $textReadAll = AmosDocumenti::t('amosdocumenti', '#addDocument');
                        $linkReadAll = '/documenti/documenti/create';
                        $checkPermNew = true;
                        ?>
                        <div class="list-items list-empty">
                            <h2 class="box-widget-subtitle"><?= AmosDocumenti::tHtml('amosdocumenti', 'Nessun documento'); ?></h2>
                        </div>
                    <?php else: ?>
                        <?php
                        $textReadAll = AmosDocumenti::t('amosdocumenti', 'Visualizza Tutti');
                        $linkReadAll = $widget->linkReadAll;
                        $checkPermNew = false;
                        ?>
                        <div class="list-items">
                            <?php foreach ($documents as $document): ?>
                                <?php
                                /** @var Documenti $document */
                                if ($document->is_folder) {
                                    $documentViewUrl = ['/documenti/documenti/own-interest-documents', 'parentId' => $document->id];
                                } else {
                                    $documentViewUrl = $document->getFullViewUrl();
                                }
                                $documentTitle = htmlspecialchars($document->titolo);
                                ?>
                                <div class="widget-listbox-option" role="option">
                                    <article class="col-xs-12 nop">
                                        <div class="container-icon">
                                            <?= AmosIcons::show('download-general', ['class' => 'icon_widget_graph'], 'dash') ?>
                                        </div>
                                        <div class="container-text">
                                            <div class="col-xs-12 listbox-date nop">
                                                <p><?= Yii::$app->getFormatter()->asDatetime($document->created_at); ?></p>
                                                <h2 class="box-widget-subtitle">
                                                    <?php
                                                    if (strlen($documentTitle) > 50) {
                                                        $stringCut = substr($documentTitle, 0, 50);
                                                        echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                                    } else {
                                                        echo $documentTitle;
                                                    }
                                                    ?>
                                                </h2>
                                            </div>
                                            <!--<div class="col-xs-12 nopl">-->
                                            <!--    <p class="box-widget-text">< ?= $document->sottotitolo; ?></p>-->
                                            <!--</div>-->
                                        </div>
                                        <div class="col-xs-12 footer-listbox nop">
                                            <span>
                                                <?= Html::a(AmosDocumenti::t('amosdocumenti', 'VISUALIZZA'), $documentViewUrl, ['class' => 'btn btn-navigation-primary']); ?>
                                            </span>
                                        </div>
                                    </article>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php Pjax::end(); ?>
        </section>
        <div class="col-xs-12 read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => ''], $checkPermNew); ?></div>
    </div>
</div>
