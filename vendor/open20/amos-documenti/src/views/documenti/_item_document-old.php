<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\StringUtils;
use open20\amos\core\views\toolbars\StatsToolbar;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\controllers\DocumentiController;
use open20\amos\notificationmanager\forms\NewsWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */

/** @var DocumentiController $appController */
$appController = Yii::$app->controller;

/** @var AmosDocumenti $documentsModule */
$documentsModule = $appController->documentsModule;

$modelViewUrl = $model->getFullViewUrl();
$document = $model->getDocumentMainFile();
$documentPresent = ($document != null);
$documentLinkPresent = (!empty($model->link_document));
$visible = isset($statsToolbar) ? $statsToolbar : false;
$enableContentDuplication = $documentsModule->enableContentDuplication;
$enableCatImgInDocView = $documentsModule->enableCatImgInDocView;
$documentCategory = $model->documentiCategorie;

$jsCount = <<<JS
    $('.link-document-id').click(function() {
        var idDoc = $(this).attr('data-key');
        $.ajax({
           url: 'increment-count-download-link?id='+idDoc,
           type: 'get',
           success: function (data) {
           }

      });
    })
JS;

$this->registerJs($jsCount);
?>

<div class="document-item-container d-flex border-bottom py-4 w-100">
    <div>

        <?= Html::a(Html::tag('h5', htmlspecialchars($model->titolo)), $modelViewUrl, ['class' => 'link-list-title ']) ?>
        <?php if ($model->descrizione_breve) { ?>
            <p class="mb-0">
                <?= htmlspecialchars($model->descrizione_breve) ?>
            </p>
        <?php } ?>
        <div class="small mb-2">
            <?= PublishedByWidget::widget([
                'model' => $model,
                'layout' => (isset(\Yii::$app->params['hideListsContentCreatorName']) && (\Yii::$app->params['hideListsContentCreatorName'] === true) ? '' : '{publisher}') . '{targetAdv}{category}' . (Yii::$app->user->can('ADMIN') ? '{status}' : '')
            ]) ?>
        </div>
        <div>
            <?php
            if ($documentPresent) {
                echo Html::a(
                    AmosDocumenti::tHtml('amosdocumenti', 'Scarica'),
                    $document->getUrl('original',true),
                    [
                        'title' => AmosDocumenti::t('amosdocumenti', 'Scarica file'),
                        'class' => 'text-uppercase font-weight-semibold mr-2',
                    ]
                );
            } else {
                if ($documentLinkPresent) {
                    echo Html::a(
                        AmosDocumenti::tHtml('amosdocumenti', 'Apri file'),
                        $model->link_document,
                        [
                            'title' => AmosDocumenti::t('amosdocumenti', 'Apri file'),
                            'class' => 'text-uppercase font-weight-semibold mr-2',
                            'target' => '_blank',
                            'data-key' => $model->id
                        ]
                    );
                }
            }

            ?>
            <span class="text-muted"><?= $fileExtension ?> (<?= $fileDimension ?>)</span>
        </div>

    </div>
    <div class="ml-auto d-flex doc-actions">
    <a href="#" title="Vedi dettaglio">
        <?php
        echo AmosIcons::show('settings', ['class' => 'icon-rounded'], 'am');
        ?>
     </a>
     <a href="#" title="Vedi dettaglio">
        <?php
        echo AmosIcons::show('search', ['class' => 'icon-rounded'], 'am');
        ?>
     </a>
    </div>
</div>



<div class="listview-container document">
    <div class="post-horizontal">
        <div class="col-sm-7 col-xs-12 nop">
            <div class="col-xs-12 nop">
                <?= ItemAndCardHeaderWidget::widget([
                    'model' => $model,
                    'publicationDateField' => 'data_pubblicazione',
                ]);
                ?>
            </div>
            <?= \Yii::$app->getFormatter()->asDate($model->getPublicatedFrom(), 'dd/MM/YYYY'); ?>
        </div>
        <div class="col-sm-7 col-xs-12 nop">
            <div class="post-content col-xs-12 nop">
                <div class="post-title col-xs-10">
                    <?= Html::a(Html::tag('h2', htmlspecialchars($model->titolo)), $modelViewUrl) ?>
                </div>
                <?php
                echo NewsWidget::widget([
                    'model' => $model,
                ]);
                ?>
                <?= ContextMenuWidget::widget([
                    'model' => $model,
                    'actionModify' => $model->getFullUpdateUrl(),
                    'actionDelete' => $model->getFullDeleteUrl(),
                    'modelValidatePermission' => 'DocumentValidate',
                    'mainDivClasses' => 'col-xs-1 nop'
                ]) ?>
                <div class="clearfix"></div>
                <div class="row nom post-wrap">
                    <div class="post-text col-xs-12">
                        <p>
                            <?= htmlspecialchars($model->descrizione_breve) ?><br>
                            <?= Html::a(AmosDocumenti::tHtml('amosdocumenti', 'Leggi tutto'), $modelViewUrl, ['class' => 'underline']) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar col-sm-5 col-xs-12">
            <div class="container-sidebar">
                <?php if ($enableCatImgInDocView) : ?>
                    <?php
                    $afterCatImgStr = '';
                    if ($documentPresent) {
                        $afterCatImgStr .= Html::tag('p', $document->name . '.' . $document->type, ['class' => 'title']);
                    }
                    if ($documentLinkPresent) {
                        $afterCatImgStr .= Html::tag('p', StringUtils::shortText($model->titolo, 80), ['class' => 'title']) .
                            Html::tag('p', StringUtils::shortText($model->link_document, 50), ['class' => 'title']);
                    }
                    ?>
                    <div class="box">
                        <div class="sidebar-documents-category-new-rl">
                            <?= Html::img($documentCategory->getAvatarUrl('square_small'), [
                                'class' => 'gridview-image',
                                'alt' => AmosDocumenti::t('amosdocumenti', 'Immagine della categoria')
                            ]); ?>
                            <p><?= $documentCategory->titolo ?></p>

                        </div>
                        <?= $afterCatImgStr ?>
                    </div>
                <?php else : ?>
                    <?php if ($documentPresent) : ?>
                        <div class="box">
                            <?php
                            if ($model->drive_file_id) {
                                echo AmosIcons::show('download-general', ['class' => 'am-4'], 'dash') .
                                    AmosIcons::show('google-drive', ['class' => 'google-sync'], 'am') .
                                    Html::tag('p', $document->name . '.' . $document->type, ['class' => 'title']);
                            } else {
                                echo AmosIcons::show('download-general', ['class' => 'am-4'], 'dash') . Html::tag('p', $document->name . '.' . $document->type, ['class' => 'title']);
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($documentLinkPresent) : ?>
                        <div class="box">
                            <?= AmosIcons::show('doc-www', ['class' => 'am-4'], 'dash') . Html::tag('p', StringUtils::shortText($model->titolo, 80), ['class' => 'title']); ?>
                            <?= Html::tag('p', StringUtils::shortText($model->link_document, 50), ['class' => 'title']); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="box post-info">
                    <?= PublishedByWidget::widget([
                        'model' => $model,
                        'layout' => (isset(\Yii::$app->params['hideListsContentCreatorName']) && (\Yii::$app->params['hideListsContentCreatorName'] === true) ? '' : '{publisher}') . '{targetAdv}{category}' . (Yii::$app->user->can('ADMIN') ? '{status}' : '')
                    ]) ?>
                    <p>
                        <strong><?= ($model->primo_piano) ? AmosDocumenti::tHtml('amosdocumenti', 'Pubblicato in prima pagina') : '' ?></strong>
                    </p>
                </div>
                <?php if ($documentPresent || $documentLinkPresent || $visible || $enableContentDuplication) : ?>
                    <div class="footer_sidebar col-xs-12 nop">
                        <?php
                        echo $this->render('_duplicate_btn', [
                            'model' => $model,
                            'isInIndex' => false,
                            'customClasses' => 'bk-btnImport pull-right btn btn-secondary m-l-10'
                        ]);
                        if ($documentPresent) {
                            echo Html::a(
                                AmosDocumenti::tHtml('amosdocumenti', 'Scarica file'),
                                $document->getUrl('original',true),
                                [
                                    'title' => AmosDocumenti::t('amosdocumenti', 'Scarica file'),
                                    'class' => 'bk-btnImport pull-right btn btn-amministration-primary',
                                ]
                            );
                        } else {
                            if ($documentLinkPresent) {
                                echo Html::a(
                                    AmosDocumenti::tHtml('amosdocumenti', 'Open file'),
                                    $model->link_document,
                                    [
                                        'title' => AmosDocumenti::t('amosdocumenti', 'Open file'),
                                        'class' => 'link-document-id bk-btnImport pull-right btn btn-amministration-primary',
                                        'target' => '_blank',
                                        'data-key' => $model->id
                                    ]
                                );
                            }
                        }

                        if ($visible) {
                            echo StatsToolbar::widget([
                                'model' => $model,
                            ]);
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
