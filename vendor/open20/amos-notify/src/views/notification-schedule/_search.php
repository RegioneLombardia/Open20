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
use open20\amos\notificationmanager\AmosNotify;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\search\NewsSearch $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="news-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'id' => 'news_form_' . $model->id,
            'class' => 'form',
            'enctype' => 'multipart/form-data',
        ]
    ]);

    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));

    ?>



    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'plugin')->widget(Select2::className(),[
                'data' => ArrayHelper::map(\open20\amos\core\models\ModelsClassname::find()->all(), 'classname', 'label'),
            'options' => ['placeholder' => 'Seleziona']
        ]) ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'content_id')->label('Id contenuto') ?>
    </div>


    <div class="col-sm-4 col-lg-4">
        <?= $form->field($model, 'send_date')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE
        ])->label('Inviato il') ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'status_schedule')
        ->widget(Select2::className(),[
            'data' => ['working' => 'In esecuzione', 'done' => 'Terminato'],
            'options' => ['placeholder' => 'Seleziona']
        ]) ->label('Stato') ?>
    </div>



    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosNotify::t('amosnews', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-outline-primary']) ?>
            <?= Html::submitButton(AmosNotify::tHtml('amosnews', 'Cerca'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <!--a><p class="text-center">Ricerca avanzata<br>
        < ?=AmosIcons::show('caret-down-circle');?>
    </p></a-->

    <?php ActiveForm::end(); ?>

</div>
