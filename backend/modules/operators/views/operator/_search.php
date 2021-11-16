<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/operators/views 
 */
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
* @var yii\web\View $this
* @var backend\modules\operators\models\search\OperatorSearch $model
* @var yii\widgets\ActiveForm $form
*/


?>
<div class="operator-search element-to-toggle" data-toggle-element="form-search">

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


	<?php if(! (\Yii::$app->user->can('PC_STRUCTURE_EDITOR') || \Yii::$app->user->can('PC_STRUCTURE_ADMIN')|| \Yii::$app->user->can('PC_STRUCTURE_APPROVER')) ){?>
	<div class="col-md-4">
        <?php
        echo $form->field($model, 'struttura')->widget(\kartik\select2\Select2::className(), [
            'data' => \yii\helpers\ArrayHelper::map(\backend\modules\operators\models\PreferenceStructure::find()->all(), 'id', 'name'),
            'options' => ['multiple' => false, 'placeholder' => 'Ricerca per struttura']
        ]); ?>

	</div>
<?php }?>

     <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Yii::t('amoscore', 'Reset'),[''] ,['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
