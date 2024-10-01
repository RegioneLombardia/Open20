<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */


use kartik\select2\Select2;
use open20\amos\core\forms\editors\Select;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use open20\amos\utility\Module;

?>

<div class="nav-item-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'id' => 'form_' . $model->id,
            'class' => 'form',
            'enctype' => 'multipart/form-data',
        ]
    ]);
    ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'title') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'alias') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'nav_container_id')->widget(Select2::className(), [
            'data' => ArrayHelper::map(\luya\cms\models\NavContainer::find()->all(), 'id', 'name'),
            'options' => ['placeholder' => 'Seleziona menu...'],
            'pluginOptions' => [
                'allowClear' => true
            ]
        ])->label(Module::t('amosutility', "Menu")) ?>
    </div>


    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Module::t('amosutility', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-outline-primary']) ?>
            <?= Html::submitButton(Module::tHtml('amosutility', 'Cerca'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <!--a><p class="text-center">Ricerca avanzata<br>
        < ?=AmosIcons::show('caret-down-circle');?>
    </p></a-->

    <?php ActiveForm::end(); ?>

</div>
