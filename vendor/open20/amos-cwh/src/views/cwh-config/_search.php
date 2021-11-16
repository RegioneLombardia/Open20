<?php

use open20\amos\cwh\AmosCwh;
use yii\helpers\Html;
use open20\amos\core\forms\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\cwh\models\search\CwhConfigSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cwh-config-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'classname') ?>

    <?= $form->field($model, 'raw_sql') ?>

    <?= $form->field($model, 'tablename') ?>

    <div class="form-group">
        <?= Html::submitButton(AmosCwh::t('amoscwh', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(AmosCwh::t('amoscwh', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
