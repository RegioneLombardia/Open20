<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views
 */

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use open20\amos\core\helpers\Html;
use open20\agid\organizationalunit\Module;
use open20\amos\core\forms\editors\Select;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitType;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitContentType;
use open20\amos\admin\models\UserProfile;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var open20\agid\organizationalunit\models\AgidOrganizationalUnitSearch $model
 * @var open20\design\components\bootstrapitalia\ActiveForm $form
 */

// enable open search modal 
$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;

?>

<div class="agid-organizational-unit-search element-to-toggle" data-toggle-element="form-search">

	<div class="col-xs-12">
		<h2><?= Module::t('amosorganizationalunit', 'Cerca per') ?>:</h2>
	</div>

	<?php
		$form = ActiveForm::begin([
			'action' => (isset($originAction) ? [$originAction] : ['index']),
			'method' => 'get',
			'options' => [
				'class' => 'form-row',
			],
		]);

		echo Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel);
	?>
		
	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'name')
				->textInput()
				->textInput(['placeholder' => Module::t('amosorganizationalunit', '#Search by name')])
				->label(Module::t('amosorganizationalunit', '#name')) 
		?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
            $form->field($model, 'agid_organizational_unit_content_type_id')->widget(Select::className(), [
                'data' => ArrayHelper::map(AgidOrganizationalUnitContentType::find()->orderBy('name')->all(), 'id', 'name'),
                'language' => substr(Yii::$app->language, 0, 2),
                'options' => [
                    'multiple' => false,
                    'placeholder' => Module::t('amosorganizationalunit', '#select_choose') . '...'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); 
        ?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
            $form->field($model, 'agid_organizational_unit_type_id')->widget(Select::className(), [
                'data' => ArrayHelper::map(AgidOrganizationalUnitType::find()->orderBy('name')->all(), 'id', 'name'),
                'language' => substr(Yii::$app->language, 0, 2),
                'options' => [
                    'multiple' => false,
                    'placeholder' => Module::t('amosorganizationalunit', '#select_choose') . '...'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); 
        ?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'short_description')
				->textInput()
				->textInput(['placeholder' => Module::t('amosorganizationalunit', '#Search by short_description')])
				->label(Module::t('amosorganizationalunit', '#short_description')) 
		?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'skills')
				->textInput()
				->textInput(['placeholder' => Module::t('amosorganizationalunit', '#Search by skills')])
		?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'headquarters_name')
				->textInput()
				->textInput(['placeholder' => Module::t('amosorganizationalunit', '#Search by headquarters_name')])
		?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'address')
				->textInput() 
				->textInput(['placeholder' => Module::t('amosorganizationalunit', '#Search by address')])
		?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'telephone_reference')
				->textInput()
				->textInput(['placeholder' => Module::t('amosorganizationalunit', '#Search by telephone_reference')])
		?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'mail_reference')
				->textInput()
				->textInput(['placeholder' => Module::t('amosorganizationalunit', '#Search by mail_reference')])
		?>
	</div>

	<div class="col-12 col-md-4">
		<?= 
			$form->field($model, 'pec_reference')
				->textInput()
				->textInput(['placeholder' => Module::t('amosorganizationalunit', '#Search by pec_reference')])
		?>
	</div>

	<?php
		/*<div class="col-12 col-md-4">
			<?= $form->field($model, 'further_information')->textInput() ?>
		</div>*/
	?>

	<?php
		/*<div class="col-12 col-md-4">
			<?= $form->field($model, 'help_box')->textInput() ?>
		</div>*/
	?>

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
			])
			->label(Module::t('amosorganizationalunit', '#updated_by')); 
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
		<?= 
			$form->field($model, 'status')->widget(Select::className(), [
				'data' => $model->getAllWorkflowStatus(),

				'language' => substr(Yii::$app->language, 0, 2),
				'options' => [
					'multiple' => false,
					'placeholder' => Module::t('amosorganizationalunit', '#select_choose') . '...',
					'value' => $model->status = \Yii::$app->request->get(end(explode("\\", $model::className())))['status']
				],
				'pluginOptions' => [
					'allowClear' => true
				],
			]); 
		?>
	</div>


	<div class="col-xs-12">
		<div class="pull-right">
			<?= Html::a(Module::t('amosorganizationalunit', '#cancel'), [''], ['class' => 'btn btn-outline-primary']) ?>
			<?= Html::submitButton(Module::t('amosorganizationalunit', '#search_for'), ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

	<div class="clearfix"></div>

	<?php ActiveForm::end(); ?>
</div>