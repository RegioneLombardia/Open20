<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-community/src/views
 */

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var open20\amos\community\models\CommunityUserFieldDefaultValSearch $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="community-user-field-default-val-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <!-- id --> <?php // echo $form->field($model, 'id') ?>

    <!-- community_user_field_id -->
    <div class="col-md-4"> <?=
        $form->field($model, 'community_user_field_id')->textInput(['placeholder' => 'ricerca per community user field id']) ?>

    </div>

    <!-- value -->
    <div class="col-md-4"> <?=
        $form->field($model, 'value')->textInput(['placeholder' => 'ricerca per value']) ?>

    </div>

    <!-- created_at --> <?php // echo $form->field($model, 'created_at') ?>

    <!-- updated_at --> <?php // echo $form->field($model, 'updated_at') ?>

    <!-- deleted_at --> <?php // echo $form->field($model, 'deleted_at') ?>

    <!-- created_by --> <?php // echo $form->field($model, 'created_by') ?>

    <!-- updated_by --> <?php // echo $form->field($model, 'updated_by') ?>

    <!-- deleted_by --> <?php // echo $form->field($model, 'deleted_by') ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Yii::t('amoscore', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
