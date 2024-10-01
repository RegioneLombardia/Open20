<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\prodottiservizi/views
 */

use open20\prodottiservizi\Module;
use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\prodottiservizi\utility\ProdottiServiziUtility;

/*
 * Personalizzare a piacimento la vista
 * $model è il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 */

$destinatari = $model->destinatariMm;
$prodottiServiziModule = Yii::$app->getModule('prodotti-e-servizi');

$cardFinalitaShow = array_slice($model->finalitaMm, 0, $prodottiServiziModule->maxProdottiServiziCardFinalitaNumber);
$cardFinalitaExtra = array_slice($model->finalitaMm, $prodottiServiziModule->maxProdottiServiziCardFinalitaNumber);
?>

<div class="card-prodotti-servizi">
    <div class="top-header-card">
        <div class="info-soluzione">
            <div class="soluzione"><span class="mdi mdi-<?= $model->soluzione->icon ?>-outline icon-card"></span><?= $model->soluzione->name ?></div>
            <div class="status <?=  ProdottiServiziUtility::getStatiClassLabel($model->status) ?>"><?= ProdottiServiziUtility::getStatoLabel($model->status) ?></div>
            <?php
            echo \open20\amos\core\forms\ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => "/prodotti-e-servizi/prodotti-servizi/update?id=" . $model->id,
                'actionDelete' => "/prodotti-e-servizi/prodotti-servizi/delete?id=" . $model->id,
                'labelDeleteConfirm' => \open20\prodottiservizi\Module::t('prodottiservizi', 'Sei sicuro di voler cancellare questo bookmark?')
            ]); ?>
        </div>
    </div>
    <div class="info-destinatario">
        <?php foreach($destinatari as $destinatarioMm) : ?>
            
                <span class="mdi mdi-<?= $destinatarioMm->destinatario->icon ?> icon-destinatario"></span><?= $destinatarioMm->destinatario->name ?>
            
        <?php endforeach; ?>
    </div>
    <div class="icon-body">
        <div class="container-title">
            <h3 class="h5"><?= Html::a($model->title, ['prodotti-servizi/view', 'id' => $model->id]) ?></h3>
        </div>
        <div class="container-description">
            <p><?= $model->description ?></p>
        </div>
    </div>
    <div class="icon-footer">
        <div class="foot-bar">
            <div class="content-finalita">
                <b>Finalità: </b>
                <?php foreach($cardFinalitaShow as $finalitaMm) : ?>
                    <span><span class="mdi mdi-circle-small"></span><?= $finalitaMm->finalita->name ?></span>
                <?php endforeach; ?>
                <?php if($cardFinalitaExtra) : ?>
                    <div class="finalita-more">
                        <div class="dropdown">
                            <a class="btn-link btn-dropdown dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only"><?=
                                Module::t(
                                    'prodottiservizi', '#SPAN_PRODOTTI_SERVIZI_CARD_EXTRA_FINALITA',
                                    ['numero_finalità' => count($cardFinalitaExtra)]
                                )
                                ?></span>
                                <span aria-hidden="true">+<?= count($cardFinalitaExtra) ?></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <div class="link-list-wrapper">
                                    <div class="link-list">
                                        <?php foreach ($cardFinalitaExtra as $finalitaMm) { ?>
                                            <div>
                                            <span class="mdi mdi-circle-small"></span><?= $finalitaMm->finalita->name ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>