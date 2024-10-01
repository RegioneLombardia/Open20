<?php

use open20\gamification\Module;
use open20\amos\organizzazioni\widgets\maps\PlaceWidget;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model open20\gamification\models\search\GamificationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gamification-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'form',
            'enctype' => 'multipart/form-data',
        ]
    ]); ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'titolo')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'descrizione')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'starting_date')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE
            ])
        ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'finish_date')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE
            ])
        ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Module::t('gamification', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-outline-primary']) ?>
            <?= Html::submitButton(Module::tHtml('gamification', 'Cerca'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>

</div>