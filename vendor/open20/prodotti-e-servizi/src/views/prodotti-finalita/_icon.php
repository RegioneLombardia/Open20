
<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\prodottiservizi/views
 */

use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\prodottiservizi\utility\ProdottiServiziUtility;

/*
 * Personalizzare a piacimento la vista
 * $model è il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 */
?>

<div class="card-container">
    <div class="icon-header grow-pict">
        <div><span class="mdi mdi-<?= $model->soluzione->icon ?>-outline"></span><?= $model->soluzione->name?></div>
        <div><?= ProdottiServiziUtility::getStatoLabel($model->status) ?></div>
        <div>
            <?php
            echo \open20\amos\core\forms\ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => "/prodotti-e-servizi/prodotti-servizi/update?id=" . $model->id,
                'actionDelete' => "/prodotti-e-servizi/prodotti-servizi/delete?id=" . $model->id,
                'labelDeleteConfirm' => \open20\prodottiservizi\Module::t('prodottiservizi', 'Sei sicuro di voler cancellare questo bookmark?')
            ]); ?>
        </div>
    </div>
    <div class="icon-body">
        <div class="container-title"><h3><?= Html::a($model->title, ['prodotti-servizi/view', 'id' => $model->id]) ?></h3></div>
    </div>
    <div class="icon-footer">
        <div class="foot-bar">
            <div><b>Finalità: </b><?= $model->finalitaMm[0]->finalita->name ?></div>
        </div>
    </div>
</div>