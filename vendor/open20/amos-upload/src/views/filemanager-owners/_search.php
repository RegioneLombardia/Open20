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
* @var open20\amos\upload\models\FilemanagerOwnersSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="filemanager-owners-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

        <?= $form->field($model, 'mediafile_id') ?>

    <?= $form->field($model, 'owner_id') ?>

    <?= $form->field($model, 'owner') ?>

    <?= $form->field($model, 'owner_attribute') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('amosupload', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('amosupload', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
