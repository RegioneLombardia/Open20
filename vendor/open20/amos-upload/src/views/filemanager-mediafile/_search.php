<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\upload
 * @category   CategoryName
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var open20\amos\upload\models\FilemanagerMediafileSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="filemanager-mediafile-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

        <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'filename') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'url') ?>

    <?= $form->field($model, 'alt') ?>

    <?php // echo $form->field($model, 'size') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'thumbs') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('amosupload', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('amosupload', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
