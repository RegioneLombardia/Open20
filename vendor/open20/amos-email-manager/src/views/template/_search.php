<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\emailmanager\AmosEmail;
use yii\widgets\ActiveForm;

$this->registerJs("
    $('#eventisearch-data_ora_inizio').change(function () {
        if ($('#eventisearch-data_ora_inizio').val() == '') {
            $('#eventisearch-data_ora_inizio-disp-kvdate .input-group-addon.kv-date-remove').remove();
        } else {
            if ($('#eventisearch-data_ora_inizio-disp-kvdate .input-group-addon.kv-date-remove').length == 0) {
                $('#eventisearch-data_ora_inizio-disp-kvdate').append('<span class=\"input-group-addon kv-date-remove\" title=\"Pulisci campo\"><i class=\"glyphicon glyphicon-remove\"></i></span>');
                initDPRemove('eventisearch-data_ora_inizio-disp');
            }
        }
    });
    $('#eventisearch-data_ora_fine').change(function () {
        if ($('#eventisearch-data_ora_fine').val() == '') {
            $('#eventisearch-data_ora_fine-disp-kvdate .input-group-addon.kv-date-remove').remove();
        } else {
            if ($('#eventisearch-data_ora_fine-disp-kvdate .input-group-addon.kv-date-remove').length == 0) {
                $('#eventisearch-data_ora_fine-disp-kvdate').append('<span class=\"input-group-addon kv-date-remove\" title=\"Pulisci campo\"><i class=\"glyphicon glyphicon-remove\"></i></span>');
                initDPRemove('eventisearch-data_ora_fine-disp');
            }
        }
    });
", yii\web\View::POS_READY);
?>
<div class="eventi-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2><?= AmosEmail::t('amosemail', 'Find') ?>:</h2></div>
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]); ?>

    <div class="col-md-4">
        <?= $form->field($model, 'name') ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'subject') ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'heading') ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'message') ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(AmosEmail::t('amosemail', 'Resetta'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosEmail::t('amosemail', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
