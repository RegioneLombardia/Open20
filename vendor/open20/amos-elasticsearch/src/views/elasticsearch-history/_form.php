<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-elasticsearch/src/views 
 */
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use open20\amos\core\forms\TextEditorWidget;
use yii\helpers\Inflector;

/**
* @var yii\web\View $this
* @var open20\elasticsearch\models\ElasticsearchHistory $model
* @var yii\widgets\ActiveForm $form
*/


 ?>
<div class="elasticsearch-history-form col-xs-12 nop">

    <?php     $form = ActiveForm::begin([
    'options' => [
    'id' => 'elasticsearch-history_' . ((isset($fid))? $fid : 0),
    'data-fid' => (isset($fid))? $fid : 0,
    'data-field' => ((isset($dataField))? $dataField : ''),
    'data-entity' => ((isset($dataEntity))? $dataEntity : ''),
    'class' => ((isset($class))? $class : '')
    ]
    ]);
     ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>
    
        <div class="row"><div class="col-xs-12"><h2 class="subtitle-form">Settings</h2><div class="col-md-8 col xs-12"><!-- search_text string -->
			<?= $form->field($model, 'search_text')->textInput(['maxlength' => true]) ?><!-- created_at datetime -->
			<?= $form->field($model, 'created_at')->widget(DateTimePicker::classname(), [
	'options' => ['placeholder' => BaseAmosModule::t('amoscore','Set time')],
	'pluginOptions' => [
		'autoclose' => true
	]
]) ?><?= RequiredFieldsTipWidget::widget(); ?><?= CloseSaveButtonWidget::widget(['model' => $model]); ?><?php ActiveForm::end(); ?></div><div class="col-md-4 col xs-12"></div></div><div class="clearfix"></div> 

</div>
</div>
