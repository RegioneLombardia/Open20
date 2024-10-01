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
 * @var frontend\models\ProdottiSoluzioniSearch $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="prodotti-soluzioni-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <!-- name -->
    <div class="col-md-12"> <?=
        $form->field($model, 'name')->textInput(['placeholder' => Module::t('prodottiservizi', 'RIcerca per Titolo')]) ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Module::t('prodottiservizi', 'Reset'), ['/prodotti-e-servizi/prodotti-soluzioni/index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Module::t('prodottiservizi', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
