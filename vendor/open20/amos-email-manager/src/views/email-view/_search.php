<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-email-manager/src/views
 */

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var open20\amos\emailmanager\models\search\EmailViewSearch $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="email-view-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <!-- id --> <?php // echo $form->field($model, 'id') ?>

    <!-- module -->
    <div class="col-md-4"> <?=
        $form->field($model, 'module')->textInput(['placeholder' => 'ricerca per module']) ?>

    </div>

    <!-- view -->
    <div class="col-md-4"> <?=
        $form->field($model, 'type')->textInput(['placeholder' => 'ricerca per tipo']) ?>

    </div>

    <!-- params -->
    <div class="col-md-4"> <?=
        $form->field($model, 'description')->textInput(['placeholder' => 'ricerca per descrizione']) ?>

    </div>

    <!-- created_at --> <?php // echo $form->field($model, 'created_at') ?>

    <!-- updated_at --> <?php // echo $form->field($model, 'updated_at') ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Yii::t('amoscore', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
