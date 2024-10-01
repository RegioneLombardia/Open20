<?php

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\videoconference\AmosVideoconference;

/**
* @var yii\web\View $this
* @var app\models\VideoconfSearch $model
* @var yii\widgets\ActiveForm $form
*/


?>

<div class="videoconf-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2>Cerca per:</h2></div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);

    echo Html::hiddenInput("enableSearch", "1");
    //echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
    ?>
    <div class="col-sm-4">
        <?= $form->field($model, 'title') ?>
    </div>
    <div  class="col-sm-4">
                <?php echo $form->field($model, 'begin_date_hour')->widget(DateControl::class, [
                    'type' => DateControl::FORMAT_DATE,
                ])->label(AmosVideoconference::t('amosvideoconference', 'Start date')) ?>
    </div>
    <div  class="col-sm-4">
                <?php echo $form->field($model, 'end_date_hour')->widget(DateControl::class, [
                    'type' => DateControl::FORMAT_DATE,
                ])->label(AmosVideoconference::t('amosvideoconference', 'End date')) ?>
    </div>
    

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosVideoconference::t('amosvideoconference', 'Cancel'), ['index'],
                ['class'=>'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosVideoconference::t('amosvideoconference', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
<!--a><p class="text-center">Ricerca avanzata<br>
            < ?=AmosIcons::show('caret-down-circle');?>
        </p></a-->
    <?php ActiveForm::end(); ?>

</div>