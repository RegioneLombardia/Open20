<?php

use open20\amos\moodle\AmosMoodle;
use open20\amos\core\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use open20\amos\moodle\utility\MoodleUtility;

/**
* @var yii\web\View $this
* @var open2\amos\moodle\models\MoodleCourse $model
* @var yii\widgets\ActiveForm $form
*/
         

?>

<div class="ticket-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2>Cerca per:</h2></div>

    <?php $form = ActiveForm::begin([
        //'action' => Yii::$app->controller->action->id,
        'action' => \yii\helpers\Url::to([ Yii::$app->controller->action->id, 'userId' =>  Yii::$app->request->getQueryParam('userId')]),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);

    echo Html::hiddenInput("enableSearch", "1");
    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
   //echo Html::hiddenInput("userId", Yii::$app->request->getQueryParam('userId'));
    ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'name') ?>
    </div>
   
   
    
    <div class="col-sm-6 col-lg-4">
        <?php
       // MoodleUtility::getCategoryList();
        $data = ArrayHelper::map(MoodleUtility::getCategoryList(), 'id', 'name');
       echo $form->field($model, 'moodle_categoryid')->label(AmosMoodle::t('amosmoodle', 'Categoria'))->widget(Select2::className(), [
                'data' => $data,
                'options' => ['placeholder' => AmosMoodle::t('amosmoodle', 'Cerca per categoria ...')],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true,
                ],
            ]
        );
        ?>
    </div>
    
    
    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(AmosMoodle::t('amosmoodle', 'Annulla'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosMoodle::t('amosmoodle', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

  
    <?php ActiveForm::end(); ?>

</div>
