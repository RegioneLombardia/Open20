<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modues/operators/views
 */

use yii\helpers\ArrayHelper;
use open20\agid\person\Module;
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use open20\agid\person\models\AgidPersonType;
use open20\amos\core\forms\editors\Select;
use open20\agid\person\models\AgidPersonContentType;
use open20\amos\admin\models\UserProfile;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var backend\modules\operators\models\AgidPersonSearch $model
 * @var yii\widgets\ActiveForm $form
 */

// enable open search modal 
$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;

?>

<div class="agid-person-search element-to-toggle" data-toggle-element="form-search">

	<div class="col-xs-12">
		<h2><?= Module::t('person', 'Cerca per') ?>:</h2>
	</div>

	<?php 
		$form = ActiveForm::begin([
			'action' => (isset($originAction) ? [$originAction] : ['index']),
			'method' => 'get',
			'options' => [
				'class' => 'default-form'
			]
		]);

		echo Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel);
	?>

	<div class="col-md-4"> 
		<?= $form->field($model, 'name')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosperson', '#name')]) ?>
	</div>

	<div class="col-md-4"> 
		<?= $form->field($model, 'surname')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosperson', '#surname')]) ?>
	</div>

	<div class="col-md-4"> 
		<?= $form->field($model, 'role')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosperson', '#role')]) ?>
	</div>

	<div class="col-md-4"> 
		<?= $form->field($model, 'telephone')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosperson', '#telephone')]) ?>
	</div>

	<?php 
		/*
			<div class="col-md-4">
				<?= 
					$form->field($model, 'agid_person_content_type_id')->widget(Select::className(), [
						'data' => ArrayHelper::map(AgidPersonContentType::find()->orderBy('name')->all(), 'id', 'name'),
						'language' => substr(Yii::$app->language, 0, 2),
						'options' => [
							'multiple' => false,
							'placeholder' => Module::t('amosservice', '#select_choose') . '...'
						],
						'pluginOptions' => [
							'allowClear' => true
						],
					]); 
				?>
			</div>
		*/
	?>

	<div class="col-md-4"> 
		<?= $form->field($model, 'email')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosperson', '#email')]) ?>
	</div>
	
	<div class="col-md-4">
		<?= 
			$form->field($model, 'agid_person_type_id')->widget(Select::className(), [
				'data' => ArrayHelper::map(AgidPersonType::find()->orderBy('name')->all(), 'id', 'name'),
				'language' => substr(Yii::$app->language, 0, 2),
				'options' => [
					'id' => 'agid_person_type_id',
					'multiple' => false,
					'placeholder' => Module::t('amosperson', '#select_choose') . '...'
				],
				'pluginOptions' => [
					'allowClear' => true
				],
			])->label(Module::t('amosperson', '#agid_person_type_id')); 
		?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'organizational_unit_of_reference')->widget(Select::className(), [
				'data' => ArrayHelper::map(open20\agid\organizationalunit\models\AgidOrganizationalUnit::find()
							->andWhere(['deleted_at' => NULL])->all(), 'id', 'name'),
				'language' => substr(Yii::$app->language, 0, 2),
				'options' => [
					'multiple' => false,
					'placeholder' => Module::t('amosorganizationalunit', '#select_choose') . '...',
					'value' => $model->organizational_unit_of_reference = \Yii::$app->request->get(end(explode("\\", $model::className())))['organizational_unit_of_reference']
				],
				'pluginOptions' => [
					'allowClear' => true
				],
			])->label(Module::t('amosperson', '#organizational_unit_of_reference')); 
		?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'date_end_assignment')->widget(Select::className(), [
				'data' => [ "si" => "si", "no" => "no" ],
				'language' => substr(Yii::$app->language, 0, 2),
				'options' => [
					'multiple' => false,
					'placeholder' => Module::t('amosorganizationalunit', '#select_choose') . '...'
				],
				'pluginOptions' => [
					'allowClear' => true
				],
			])->label(Module::t('amosperson', 'Incarico concluso')); 
		?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'updated_by')->widget(Select::className(), [
				'data' => ArrayHelper::map(UserProfile::find()->andWhere(['deleted_at' => NULL])->all(), 'user_id', function($model) {
					return $model->nome . " " . $model->cognome;
				}),
				'language' => substr(Yii::$app->language, 0, 2),
				'options' => [
					'multiple' => false,
					'placeholder' => Module::t('amosorganizationalunit', '#select_choose') . '...'
				],
				'pluginOptions' => [
					'allowClear' => true
				],
			])->label(Module::t('amosperson', '#updated_by')); 
		?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'updated_from')->widget(DateControl::className(), [
				'type' => DateControl::FORMAT_DATE,
				'value' => $model->updated_from = \Yii::$app->request->get(end(explode("\\", $model::className())))['updated_from'],
			])->label(Module::t('amosorganizationalunit', '#updated_from')); 
		?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'updated_to')->widget(DateControl::className(), [
				'type' => DateControl::FORMAT_DATE,
				'value' => $model->updated_to = \Yii::$app->request->get(end(explode("\\", $model::className())))['updated_to'],
			])->label(Module::t('amosorganizationalunit', '#updated_to')); 
		?>
	</div>



	<div class="col-12 col-md-4">
		<!-- TODO ADD kartik\daterange\DateRangePicker -->
	</div>
	
	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'status')->widget(Select::className(), [
				'data' => $model->getAllWorkflowStatus(),

				'language' => substr(Yii::$app->language, 0, 2),
				'options' => [
					'multiple' => false,
					'placeholder' => Module::t('amosperson', '#select_choose') . '...',
					'value' => \Yii::$app->request->get(end(explode("\\", $model::className())))['status']
				],
				'pluginOptions' => [
					'allowClear' => true
				],
			]); 
		?>
	</div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Module::t('amosservice', '#cancel'), [''], ['class' => 'btn btn-outline-primary']) ?>
            <?= Html::submitButton(Module::t('amosservice', '#search_for'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

	<div class="clearfix"></div>

	<?php ActiveForm::end(); ?>
</div>