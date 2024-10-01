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
        'action' => Yii::$app->controller->action->id.'?notification_id='.\Yii::$app->request->get('notification_id').'&schedule_id='.\Yii::$app->request->get('schedule_id'),
        'method' => 'get',
        'options' => [
            'id' => 'news_form_' . $model->id,
            'class' => 'form',
            'enctype' => 'multipart/form-data',
        ]
    ]);
    ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'nome')->label('Nome') ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'cognome')->label('Cognome') ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'nome')->label('Email') ?>
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
