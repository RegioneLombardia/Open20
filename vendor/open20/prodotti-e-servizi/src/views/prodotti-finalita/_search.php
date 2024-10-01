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
use open20\prodottiservizi\Module;

/**
 * @var yii\web\View $this
 * @var frontend\models\ProdottiFinalitaSearch $model
 * @var yii\widgets\ActiveForm $form
 */

?>
<div class="prodotti-finalita-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <!-- id --> <?php // echo $form->field($model, 'id') ?>

    <!-- name -->
    <div class="col-md-4"> <?=
        $form->field($model, 'name')->textInput(['placeholder' => Module::t('prodottiservizi', 'Ricerca per Titolo')]) ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Module::t('prodottiservizi', 'Reset'), ['/prodotti-e-servizi/prodotti-finalita/index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('prodottiservizi', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
