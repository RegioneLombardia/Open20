<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\assets\ModuleSondaggiAsset;
use open20\amos\sondaggi\widgets\graphics\WidgetGraphicsUltimiSondaggi;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View $this
 * @var ActiveDataProvider $lista
 * @var WidgetGraphicsUltimiSondaggi $widget
 * @var string $toRefreshSectionId
 */

ModuleSondaggiAsset::register($this);

$moduleSondaggi = \Yii::$app->getModule(AmosSondaggi::getModuleName());
$listaModels = $lista->getModels();

?>

<div class="box-widget-header">

    <?php
    if (isset($moduleSondaggi) && !$moduleSondaggi->hideWidgetGraphicsActions) {
        WidgetGraphicsActions::widget([
            'widget' => $widget,
            'tClassName' => AmosSondaggi::className(),
            'actionRoute' => '/sondaggi/dashboard/create',
            'toRefreshSectionId' => $toRefreshSectionId
        ]);
    } ?>

    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('sondaggi', ['class' => 'am-2'], AmosIcons::IC) ?>
            <?= AmosSondaggi::tHtml('amossondaggi', 'Ultimi sondaggi') ?>
        </h2>
    </div>

    <?php
    if (count($listaModels) == 0) {
        $textReadAll = AmosSondaggi::t('amossondaggi', '#addSondaggio');
        $linkReadAll = '/sondaggi/dashboard/create';
        $checkPermNew = true;
    } else {
        $textReadAll = AmosSondaggi::t('amossondaggi', '#showAll') . AmosIcons::show('chevron-right');
        $linkReadAll = ['/sondaggi/pubblicazione/all'];
        $checkPermNew = false;
    }
    ?>
    <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => ''], $checkPermNew); ?></div>
</div>


<div class="box-widget box-widget-column latest-sondaggi">

    <section>
        <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>

        <?php if (count($listaModels) == 0): ?>
            <div class="list-items list-empty"><h3><?= AmosSondaggi::t('amossondaggi', '#noSondaggi') ?></h3></div>
        <?php endif; ?>

        <div class="list-items">
            <?php foreach ($listaModels as $sondaggio): ?>
                <div class="widget-listbox-option" role="option">
                    <article class="wrap-item-box">
                        <div>
                            <div class="container-img">
                                <?php
                                $url = '/img/img_default.jpg';
                                if (!is_null($sondaggio->file)) {
                                    $url = $sondaggio->file->getUrl('dashboard_sondaggi', false, true);
                                }
                                ?>
                                <?= Html::img($url, ['class' => 'img-responsive', 'alt' => AmosSondaggi::t('amossondaggi', 'Immagine del sondaggio')]); ?>
                            </div>
                        </div>

                        <div class="container-text">
                            <h2 class="box-widget-subtitle">
                                <?php
                                if (strlen($sondaggio->titolo) > 55) {
                                    $stringCut = substr($sondaggio->titolo, 0, 55);
                                    echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                } else {
                                    echo $sondaggio->titolo;
                                }
                                ?>
                            </h2>
                            <p class="box-widget-text">
                                <?php
                                if (strlen($sondaggio->descrizione) > 80) {
                                    $stringCut = substr($sondaggio->descrizione, 0, 80);
                                    echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                } else {
                                    echo $sondaggio->descrizione;
                                }
                                ?>
                            </p>
                        </div>
                        <div class="footer-listbox">
                            <?= Html::a(AmosSondaggi::t('amossondaggi', '#readMore'), ['/sondaggi/pubblicazione/compila', 'id' => $sondaggio->id], ['class' => 'btn btn-navigation-primary']); ?>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
        <?php Pjax::end(); ?>
    </section>
</div>
