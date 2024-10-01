<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\attachments\models\File;
use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiAsset;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\utility\DocumentsUtility;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti;
use yii\data\ActiveDataProvider;
use yii\web\View;

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
$alwaysLinkToViewWidgetGraphicLastDocs = $moduleDocumenti->alwaysLinkToViewWidgetGraphicLastDocs;

?>
<div class="box-widget-header">
    <?php if (isset($moduleDocumenti) && !$moduleDocumenti->hideWidgetGraphicsActions) { ?>
        <?= WidgetGraphicsActions::widget([
            'widget' => $widget,
            'tClassName' => AmosDocumenti::className(),
            'actionRoute' => '/documenti/documenti/create',
            'toRefreshSectionId' => $toRefreshSectionId
        ]);
    } ?>

    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('file-text-o', [], AmosIcons::DASH) ?>
            <span class="pluginName"> <?= $widget->widgetTitle ?> </span>
        </h2>
    </div>

    <?php
    if (count($documents) == 0) {
        $textReadAll = AmosDocumenti::t('amosdocumenti', '#addDocument');
        $linkReadAll = '/documenti/documenti/create';
        $checkPermNew = true;
    } else {
        $textReadAll = AmosDocumenti::t('amosdocumenti', 'Visualizza Tutti') . AmosIcons::show('chevron-right');
        $linkReadAll = $widget->linkReadAll;
        $checkPermNew = false;
    }
    ?>

    <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => ''], $checkPermNew); ?></div>
</div>

<div class="box-widget box-widget-column latest-documents">
    <section>
        <h2 class="sr-only"><?= $widget->getLabel() ?></h2>
        <?php if (count($documents) == 0): ?>
            <div class="list-items list-empty"><h3><?= AmosDocumenti::tHtml('amosdocumenti', 'Nessun documento'); ?></h3></div>
        <?php else: ?>
            <div class="list-items">
                <?php foreach ($documents as $document): ?>
                    <?php
                    /** @var Documenti $document */
                    $documentViewUrl = '';
                    $icon = DocumentsUtility::getDocumentIcon($document, true);
                    $documentInfo = $document->getDocumentMainFile();
                    $documentInfoIsFile = ($documentInfo instanceof File);
                    $documentIconboxTitle = AmosDocumenti::t('amosdocumenti', 'Scarica file');
                    $documentIconboxUrl = null;

                    if ($document->is_folder) {
                        $documentViewUrl = ['/documenti/documenti/own-interest-documents', 'parentId' => $document->id];
                        $documentIconboxUrl = ['/documenti/documenti/own-interest-documents', 'parentId' => $document->id];
                    } else {
                        $documentViewUrl = $document->getFullViewUrl();
                        if ($alwaysLinkToViewWidgetGraphicLastDocs) {
                            $documentIconboxUrl = $documentViewUrl;
                            $documentIconboxTitle = AmosDocumenti::t('amosdocumenti', 'Apri');
                        } else {
                            if ($documentInfoIsFile) {
                                $documentIconboxUrl = $documentInfo->getUrl('original',true);
                            }
                        }
                    }

                    $documentTitle = htmlspecialchars($document->titolo);
                    if (strlen($documentTitle) > 150) {
                        $stringCut = substr($documentTitle, 0, 150);
                        $documentTitle = substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                    }
                    ?>
                    <div class="widget-listbox-option" role="option">
                        <article class="wrap-item-box text-center">
                            <?php
                            $linkOptions = ['title' => $documentIconboxTitle, 'class' => 'iconbox-link'];
                            if (!empty($document->link_document)) {
                                $linkOptions['target'] = '_blank';
                                $documentIconboxUrl = $document->link_document;
                            }
                            ?>
                            <?= Html::a('
                                    <div class="widget-iconbox-container">
                                        <div class="container-img">
                                            ' . AmosIcons::show($icon, ['class' => 'icon_widget_graph'], 'dash') . '
                                        </div>
                                        <div class="container-text">
                                            <h2 class="box-widget-subtitle">
                                                ' . $documentTitle . '
                                            </h2>
                                            <div class="box-widget-info-bottom">
                                                <span> ' . ($documentInfoIsFile ? $documentInfo->formattedSize : '') . '</span>
                                            </div>
                                        </div>
                                    </div>
                                ',
                                ((isset($documentIconboxUrl) && !empty($documentIconboxUrl)) ? $documentIconboxUrl : ''),
                                $linkOptions); ?>

                            <div class="box-widget-info-top">
                                <p><?= Yii::$app->getFormatter()->asDatetime($document->created_at); ?></p>
                            </div>
                            <?php if (!$alwaysLinkToViewWidgetGraphicLastDocs): ?>
                                <div class="footer-listbox footer-listbox-center">
                                    <?= Html::a('<span class="sr-only">' . AmosDocumenti::t('amosdocumenti', 'VISUALIZZA') . '</span>' . AmosIcons::show('info'), $documentViewUrl, ['class' => 'btn-action']); ?>
                                </div>
                            <?php endif; ?>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
