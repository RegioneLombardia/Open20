<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/prodotti-e-servizi/src/views
 */

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var open20\prodottiservizi\models\search\ProdottiServiziAccordionSearch $model
 * @var yii\widgets\ActiveForm $form
 */

?>
<div class="prodotti-servizi-accordion-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <!-- title -->
    <div class="col-md-4"> <?=
        $form->field($model, 'title')->textInput(['placeholder' => 'ricerca per title']) ?>

    </div>

    <!-- description -->
    <div class="col-md-4"> <?=
        $form->field($model, 'description')->textInput(['placeholder' => 'ricerca per description']) ?>

    </div>

    <!-- is_visible -->
    <div class="col-md-4"> <?=
        $form->field($model, 'is_visible')->textInput(['placeholder' => 'ricerca per is visible']) ?>

    </div>

    <!-- position -->
    <div class="col-md-4"> <?=
        $form->field($model, 'position')->textInput(['placeholder' => 'ricerca per position']) ?>

    </div>

    <!-- prodotto_id -->
    <div class="col-md-4"> <?=
        $form->field($model, 'prodotto_id')->textInput(['placeholder' => 'ricerca per prodotto id']) ?>

    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Yii::t('amoscore', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
