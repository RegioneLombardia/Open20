<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-newsletter/src/views
 */

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var amos\newsletter\models\NewsletterSearch $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="newsletter-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>
    <div class="col-xs-12"><h2><?= \amos\newsletter\Module::t('amosnewsletter', 'Cerca per') ?>:</h2></div>


    <!-- id --> <?php // echo $form->field($model, 'id') ?>

    <!-- newsletter_template_id -->
    <div class="col-md-4"> <?=
        $form->field($model, 'newsletter_template_id')->textInput(['placeholder' => 'ricerca per newsletter template id']) ?>

    </div>


    <div class="col-md-4">
        <?=
        $form->field($model, 'newsletterTemplate')->textInput(['placeholder' => 'ricerca per '])->label('');
        ?>
    </div>
    <!-- subject -->
    <div class="col-md-4"> <?=
        $form->field($model, 'subject')->textInput(['placeholder' => 'ricerca per subject']) ?>

    </div>

    <!-- text -->
    <div class="col-md-4"> <?=
        $form->field($model, 'text')->textInput(['placeholder' => 'ricerca per text']) ?>

    </div>

    <!-- welcome_type -->
    <div class="col-md-4"> <?=
        $form->field($model, 'welcome_type')->textInput(['placeholder' => 'ricerca per welcome type']) ?>

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
