<?php

use open20\amos\core\forms\ActiveForm;
use open20\amos\cwh\AmosCwh;
use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;

/**
 * @var yii\web\View $this
 * @var open20\amos\cwh\models\search\CwhConfigSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cwh-config-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

        <div class="col-sm-6 col-lg-4">
            <?= $form->field($model, 'user_id') ?>
        </div>

        <div class="col-sm-6 col-lg-4">
            <?= $form->field($model, 'item_name') ?>
        </div>

        <div class="col-sm-6 col-lg-4">
            <?= $form->field($model, 'cwh_nodi_id') ?>
        </div>

        <div class="col-xs-12">
            <div class="pull-right">
                <?= Html::submitButton(AmosCwh::t('amoscwh', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
                <?= Html::resetButton(AmosCwh::t('amoscwh', 'Annulla'), ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>

        <div class="clearfix"></div>

        <a><p class="text-center">Ricerca avanzata<br>
                <?=AmosIcons::show('caret-down-circle');?>
        </p></a>


    <?php ActiveForm::end(); ?>

</div>
