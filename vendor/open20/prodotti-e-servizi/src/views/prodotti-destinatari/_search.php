<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/prodotti-e-servizi/src/views
 */

use open20\amos\core\helpers\Html;
use open20\prodottiservizi\utility\ProdottiServiziUtility;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\prodottiservizi\Module;

/**
 * @var yii\web\View $this
 * @var frontend\models\ProdottiDestinatariSearch $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="prodotti-destinatari-search element-to-toggle" data-toggle-element="form-search">

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
        $form->field($model, 'name')->textInput(['placeholder' => Module::t('prodottiservizi', 'Ricerca per Nome')]) ?>
    </div>

    <!-- parent -->
    <div class="col-md-12">
        <?= $form->field($model, 'parent')->widget(\kartik\select2\Select2::className(), [
            'data' => ProdottiServiziUtility::getAmbiti(),
            'hideSearch' => true,
            'options' => [
                'id' => 'parent-ambito',
                'placeholder' => Module::t('prodottiservizi', 'Ricerca per Ambito'),
                'multiple' => true
                ],
            'pluginOptions' => [ 'allowClear' => true ]
        ]); ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Module::t('prodottiservizi', 'Reset'), ['/prodotti-e-servizi/prodotti-destinatari/index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Module::t('prodottiservizi', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
