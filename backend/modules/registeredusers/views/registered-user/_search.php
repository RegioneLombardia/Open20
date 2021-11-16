<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/registeredusers/views
 */
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
* @var yii\web\View $this
* @var backend\modules\registeredusers\models\search\OperatorSearch $model
* @var yii\widgets\ActiveForm $form
*/


?>
<div class="registereduser-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
    'action' => (isset($originAction) ? [$originAction] : ['index']),
    'method' => 'get',
    'options' => [
    'class' => 'default-form'
    ]
    ]);
    ?>

    <!-- id -->  <?php // echo $form->field($model, 'id') ?>

 <!-- nome -->
<div class="col-md-4"> <?= 
$form->field($model, 'nome')->textInput(['placeholder' => 'ricerca per nome' ]) ?>

 </div> 

<!-- cognome -->
<div class="col-md-4"> <?= 
$form->field($model, 'cognome')->textInput(['placeholder' => 'ricerca per cognome' ]) ?>

 </div> 

<!-- codice_fiscale -->
<div class="col-md-4"> <?= 
$form->field($model, 'codice_fiscale')->textInput(['placeholder' => 'ricerca per codice fiscale' ]) ?>

 </div>
	<div class="col-md-4"> <?=
        $form->field($model, 'status')->widget(\kartik\select2\Select2::className(), [
            'data' => [
                '10' => \Yii::t("registeredusers", "Attivo"),
                '0' => \Yii::t("registeredusers", "Disattivo"),
            ],
            'options' => ['placeholder' =>  \Yii::t("preferencelandings","Seleziona...")]
        ])->label(\Yii::t("preferencelandings","Stato"))?>

	</div>

<!-- sesso -->
<div class="col-md-4"> 
 <?php
            echo $form->field($model, 'comune_residenza_id')->widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\open20\amos\comuni\models\IstatComuni::find()->all(), 'id', 'nome'),
                'options' => ['multiple' => false, 'placeholder' => 'Ricerca per comune']
            ]); 
            ?>


 </div> 


     <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Yii::t('amoscore', 'Reset'),[''] ,['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
