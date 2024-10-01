<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\prodottiservizi/views
 */

use open20\amos\core\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\prodottiservizi\Module;
use open20\prodottiservizi\utility\ProdottiServiziUtility;

/**
 * @var yii\web\View $this
 * @var frontend\models\search\ProdottiServiziSearch $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="prodotti-servizi-search element-to-toggle" data-toggle-element="form-search">

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
        $form->field($model, 'title')->textInput(['placeholder' => 'ricerca per ' . Module::t('prodottiservizi', 'Title')]) ?>
    </div>

    <!-- status -->
    <div class="col-md-4">
        <?= $form->field($model, 'status')->widget(\kartik\select2\Select2::className(), [
            'data' => ProdottiServiziUtility::getStati(),
            'hideSearch' => true,
            'options' => ['id' => 'status', 'placeholder' => \open20\prodottiservizi\Module::t('prodottiservizi', 'Ricerca per Stato')],
            'pluginOptions' => [ 'allowClear' => true ]
        ]); ?>
    </div>

    <!-- scope -->
    <div class="col-md-4">
        <?= $form->field($model, 'scope')->widget(\kartik\select2\Select2::className(), [
            'data' => ProdottiServiziUtility::getAmbiti(),
            'hideSearch' => true,
            'options' => ['id' => 'parent-ambito', 'placeholder' => \open20\prodottiservizi\Module::t('prodottiservizi', 'Ricerca per Ambito')],
            'pluginOptions' => [ 'allowClear' => true ]
        ]); ?>
    </div>

    <!-- destinatari -->
    <div class="col-md-4">
        <?= $form->field($model, 'prodotti_servizi_destinatari_mm')->widget(\kartik\select2\Select2::className(), [
            'data' => $destinatari,
            'options' => [
                'id' => 'destinatari_id',
                'placeholder' => \open20\prodottiservizi\Module::t('prodottiservizi', 'Ricerca per Destinatari'),
                'multiple' => true,
            ]
        ]); ?>
    </div>

    <!-- solution -->
    <div class="col-md-4">
        <?= $form->field($model, 'solution')->widget(\kartik\select2\Select2::className(), [
            'data' => $soluzioni,
            'hideSearch' => true,
            'options' => [
                'id' => 'solution_id',
                'placeholder' => \open20\prodottiservizi\Module::t('prodottiservizi', 'Ricerca per Soluzione'),
                'multiple' => true,
            ],
            'pluginOptions' => [ 'allowClear' => true ]
        ]); ?>
    </div>

    <!-- finalità -->
    <div class="col-md-4">
        <?= $form->field($model, 'prodotti_servizi_finalita_mm')->widget(\kartik\select2\Select2::className(), [
            'data' => $finalita,
            'options' => [
                'id' => 'finalita_id',
                'placeholder' => \open20\prodottiservizi\Module::t('prodottiservizi', 'Ricerca per Finalità'),
                'multiple' => true,
                'selected' => 'all'
            ]
        ]); ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Module::t('prodottiservizi', 'Reset'), ['/prodotti-e-servizi/prodotti-servizi/amministra'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Module::t('prodottiservizi', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
