<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views
 */
// use open20\design\components\bootstrapitalia\ActiveForm;

// use open20\design\components\bootstrapitalia\Select;


use open20\amos\attachments\components\CropInput;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\documenti\models\Documenti;
use open20\amos\seo\widgets\SeoWidget;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use kartik\select2\Select2;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitContentType;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitType;
use open20\agid\organizationalunit\Module;
use open20\agid\person\models\AgidPerson;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use open20\agid\service\models\AgidService;

/**
 * @var View $this
 * @var AgidOrganizationalUnit $model
 * @var ActiveForm $form
 */

?>


<?php 
/** CONTENT PAGE SCRIPTS */

$script = <<< JS

	$('#agidorganizationalunit-telephone_reference').keyup(function(){
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});

	$('#agidorganizationalunit-cap').keyup(function(){
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	
JS;

$this->registerJs($script);
?>




<div class="agid-organizational-unit-form ">
	<?php 
		$form = ActiveForm::begin([
			'options' => [
				'id' => 'agid-organizational-unit_' . ((isset($fid)) ? $fid : 0),
				'data-fid' => (isset($fid)) ? $fid : 0,
				'data-field' => ((isset($dataField)) ? $dataField : ''),
				'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
				'class' => ((isset($class)) ? $class : ''),
			],
		]);
	?>


	<div class="row">
		<!--nome-->
		<div class="col-xs-12 section-form">
			<h2 class="subtitle-form">Nome</h2>
			<div class="row">
				<div class="col-md-6">
					<?=
						$form->field($model, 'name')->textInput([
							'maxlength' => true,
						])
					?>
				</div>
			</div>
			
		</div>

		<!--tipologia-->
		<div class="col-xs-12 section-form">
			<h2 class="subtitle-form">Tipologia</h2>
			<div class="row">
				<div class="col-md-6">
					<?=
						$form->field($model, 'agid_organizational_unit_content_type_id')->widget(Select::classname(), [
							'data' => ArrayHelper::map(AgidOrganizationalUnitContentType::findRedactor()->asArray()->all(), 'id', 'name'),
							'language' => substr(Yii::$app->language, 0, 2),
							'options' => [
								'id' => 'agid_organizational_unit_content_type_id',
								'multiple' => false,
								'placeholder' => 'Seleziona ...',
							],
							'pluginOptions' => [
								'allowClear' => true,
							],
						])
					?>
				</div>
				<div class="col-md-6">
					<?=
						$form->field($model, 'agid_organizational_unit_type_id')->widget(Select::classname(), [
							'data' => ArrayHelper::map(AgidOrganizationalUnitType::find()->asArray()->all(), 'id', 'name'),
							'language' => substr(Yii::$app->language, 0, 2),
							'options' => [
								'id' => 'agid_organizational_unit_type_id',
								'multiple' => false,
								'placeholder' => 'Seleziona ...',
							],
							'pluginOptions' => [
								'allowClear' => true,
							],
						])
					?>
				</div>
			</div>
			
		</div>


		<!--immagine-->
		<div class="col-xs-12 section-form">
			<h2 class="subtitle-form">Immagine</h2>
			<div class="row">
				<div class="col-md-6">
					<?=
						$form->field($model, 'logo_image')->widget(CropInput::classname(), [
							'jcropOptions' => ['aspectRatio' => '1.7'],
						])->label(Module::t('amosorganizationalunit', 'logo_image'))
					?>
				</div>
			</div>
			
		</div>

		<!--altre info-->
		<div class="col-xs-12 section-form">
			<h2 class="subtitle-form">Altre informazioni</h2>
			<div class="row">
				<div class="col-md-6 col-xs-12">
					<?=
						$form->field($model, 'short_description')->widget(TextEditorWidget::className(), [
								'clientOptions' => [
															'lang' => substr(Yii::$app->language, 0, 2),
								],
						]);
					?>
				</div>
				<div class="col-md-6 col-xs-12">
					<?=
						$form->field($model, 'skills')->widget(TextEditorWidget::className(), [
							'clientOptions' => [
								'lang' => substr(Yii::$app->language, 0, 2),
							],
						]);
					?>
				</div>
				<div class="col-md-6 col-xs-12">
					<?=
						$form->field($model, 'further_information')->widget(TextEditorWidget::className(), [
							'clientOptions' => [
													'lang' => substr(Yii::$app->language, 0, 2),
							],
						]);
					?>
				</div>
				<div class="col-md-6 col-xs-12">
					<?php
						//  view data
						foreach ($model->agidOrganizationalUnitDocumentiMm as $key => $value) {
							$agid_organizational_unit_documenti_mm[] = $value->documenti_id;
						}
					?>
					<?= 
						$form->field($model, 'agid_organizational_unit_documenti_mm[]')->widget(Select2::className(),[
							'data' => ArrayHelper::map(Documenti::find()->orderBy(['titolo'=> SORT_ASC])->asArray()->all(), 'id', 'titolo'),
							'options' => [
								'placeholder' => Module::t('amosorganizationalunit', 'Seleziona...'),
								'multiple' => true,
								'value' => isset($agid_organizational_unit_documenti_mm) ? $agid_organizational_unit_documenti_mm : null,
							],
						])->label(Module::t('amosorganizationalunit', 'Allegati'))
					?>
				</div>
			</div>
			
		</div>

		<!--referenti-->
		<div class="col-xs-12 section-form">
			<h2 class="subtitle-form">Referenti</h2>
			<div class="row">
				<div class="col-md-6 col-xs-12">
					<?=
						$form->field($model, 'agid_person_president_id')->widget(Select::classname(), [
							'data' => ArrayHelper::map(AgidPerson::find()->asArray()->all(), 'id', function($attribute){
								return $attribute['name'] . " " . $attribute['surname'];
							}),
							'language' => substr(Yii::$app->language, 0, 2),
							'options' => [
								'id' => 'user_profile_president_id',
								'multiple' => false,
								'placeholder' => 'Seleziona ...',
							],
							'pluginOptions' => [
								'allowClear' => true,
							]
						]);
					?>
				</div>
				<div class="col-md-6 col-xs-12">
					<?=
						$form->field($model, 'agid_person_vice_president_id')->widget(Select2::className(),[
							'data' => ArrayHelper::map(AgidPerson::find()->asArray()->all(), 'id', function($attribute){
								return $attribute['name'] . " " . $attribute['surname'];
							}),
							'language' => substr(Yii::$app->language, 0, 2),
							'options' => [
								'id' => 'user_profile_vice_president_id',
								'multiple' => false,
								'placeholder' => 'Seleziona ...',
							],
							'pluginOptions' => [
								'allowClear' => true,
							]
						]);
					?>
				</div>
				<div class="col-md-6 col-xs-12">
					<?php
						//  view data
						foreach ($model->agidOrganizationalOrganizationalUnitMm as $key => $value) {
							$agid_organizational_organizational_unit_mm[] = $value->agid_organizational_unit_father_id;
						}
					?>
					<?= 
						$form->field($model, 'agid_organizational_organizational_unit_mm[]')->widget(Select2::className(),[
							'data' => ArrayHelper::map(AgidOrganizationalUnit::find()
														->andWhere(['NOT', ['id' => $model->id] ])
														->asArray()->all(), 'id', 'name'),
							'options' => [
								'placeholder' => Module::t('amosorganizationalunit', 'Seleziona...'),
								'multiple' => true,
								'value' => isset($agid_organizational_organizational_unit_mm) ? $agid_organizational_organizational_unit_mm : null,
							],
						])->label(Module::t('amosorganizationalunit', 'Legami con altre strutture'))
					?>
				</div>
				<div class="col-md-6 col-xs-12">
					<?php 
						//  view data
						foreach ($model->agidOrganizationalUnitServicesMm as $key => $value) {
							$agid_organizational_unit_service_mm[] = $value->agid_service_id;
						}
					?>
					<?= 
						$form->field($model, 'agid_organizational_unit_service_mm[]')->widget(Select2::className(),[
							'data' => ArrayHelper::map(AgidService::find()->orderBy(['name'=> SORT_ASC])->asArray()->all(), 'id', 'name'),
							'options' => [
								'placeholder' => Module::t('amosorganizationalunit', 'Seleziona...'),
								'multiple' => true,
								'value' => isset($agid_organizational_unit_service_mm) ? $agid_organizational_unit_service_mm : null,
							],
						])->label(Module::t('amosorganizationalunit', 'Servizi'))
					?>
				</div>
			</div>
			
		</div>

		<!--riferimenti-->
		<div class="col-xs-12 section-form">
			<h2 class="subtitle-form">Riferimenti</h2>
			<div class="row">
				<div class="col-md-6 col-xs-12">
					<?=
						$form->field($model, 'headquarters_name')->textInput([
							'maxlength' => true,
						])
					?>
				</div>
				<div class="col-md-4 col-xs-6">
					<?=
						$form->field($model, 'address')->textInput([
							'maxlength' => true,
						])
					?>
				</div>
				<div class="col-md-2 col-xs-6">
					<?=
						$form->field($model, 'cap')->textInput([
							'maxlength' => true,
						])
					?>
				</div>
			</div>
		</div>

		<div class="col-xs-12 section-form">
			<div class="row">
				<div class="col-md-6 col-xs-12">
					<?=
						$form->field($model, 'telephone_reference')->textInput([
							'maxlength' => '11',
							'placeholder' => Module::t('amosorganizationalunit', 'Numero di telefono'),
						])
					?>
				</div>
				<div class="col-md-6 col-xs-12">
					<?=
						$form->field($model, 'mail_reference')->textInput([
							'maxlength' => true,
						])
					?>
				</div>
			</div>	
		</div>

		<div class="col-xs-12 section-form">
			<div class="row">
				<div class="col-md-6 col-xs-12">
					<?=
						$form->field($model, 'pec_reference')->textInput([
							'maxlength' => true,
						])
					?>
				</div>
				<div class="col-md-6 col-xs-12">
					<?=
						$form->field($model, 'website_url')->textInput([
							'maxlength' => true,
						])
					?>
				</div>
				<div class="col-md-6 col-xs-12">
					<?=
						$form->field($model, 'public_hours')->widget(TextEditorWidget::className(), [
							'clientOptions' => [
								'id' => 'public_hours',
								'lang' => substr(Yii::$app->language, 0, 2),
							],
						]);
					?>
				</div>
			</div>			
		</div>
		
		<!--box aiuto-->
		<div class="col-xs-12 section-form">
			<h2 class="subtitle-form">Box d'aiuto</h2>
			<div class="row">
				<div class="col-md-6">
					<?=
						$form->field($model, 'help_box')->widget(TextEditorWidget::className(), [
							'clientOptions' => [
													'id' => 'help_box',
													'lang' => substr(Yii::$app->language, 0, 2),
							],
						]);
					?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12 ">

			<?= Html::tag('h2', Module::t('amosorganizationalunit', '#tassonomia_argomenti'), ['class' => 'subtitle-form']) ?>

			<?php

				$moduleCwh = Yii::$app->getModule('cwh'); 

				$scope = null;
				if (!empty($moduleCwh)) {
					$scope = $moduleCwh->getCwhScope();
				}

				echo \open20\amos\cwh\widgets\DestinatariPlusTagWidget::widget([
					'model' => $model,
					'moduleCwh' => $moduleCwh,
					'scope' => $scope
				]);
			?>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<?php if (Yii::$app->getModule('seo')) : ?>
				<?=
					AccordionWidget::widget([
						'items' => [
							[
								'header' => Module::t('amosperson', '#settings_seo_title'),
								'content' => SeoWidget::widget([
									'contentModel' => $model,
								]),
							]
						],
						'headerOptions' => ['tag' => 'h2'],
						'options' => Yii::$app->user->can('ADMIN') ? [] : ['style' => 'display:none;'],
						'clientOptions' => [
							'collapsible' => true,
							'active' => 'false',
							'icons' => [
								'header' => 'ui-icon-amos am am-plus-square',
								'activeHeader' => 'ui-icon-amos am am-minus-square',
							]
						],
					]);
				?>
			<?php endif; ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<?=
				WorkflowTransitionButtonsWidget::widget([
					'form' => $form,
					'model' => $model,
					'workflowId' => AgidOrganizationalUnit::AGID_ORGANIZATIONAL_UNIT_WORKFLOW,
					'viewWidgetOnNewRecord' => true,
					
					'closeButton' => Html::a( Module::t('agid-organizational-unit', 'Annulla'),
						$referrer ? $referrer : '/organizationalunit/agid-organizational-unit',
						[
							'class' => 'btn btn-outline-primary'
						]
					),
					'draftButtons' => [
						'default' => [
							'button' => Html::submitButton(
								Module::t('organizational_unit', 'Salva'),
								['class' => 'btn btn-primary']
							),
						],
					],
					
					'initialStatusName' => "DRAFT",
					'initialStatus' => AgidOrganizationalUnit::AGID_ORGANIZATIONAL_UNIT_WORKFLOW_STATUS_DRAFT,
					
				]);
			?>
		</div>
	</div>

	<?php ActiveForm::end();?>

</div>
