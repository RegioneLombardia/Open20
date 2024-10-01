<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views
 */
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use open20\agid\dataset\Module;
use open20\amos\core\forms\editors\Select;

/**
 * @var yii\web\View $this
 * @var app\models\AgidDatasetSearch $model
 * @var yii\widgets\ActiveForm $form
 */

// enable open search modal 
$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;

?>

<div class="agid-dataset-search element-to-toggle" data-toggle-element="form-search">

	<?php 
		$form = ActiveForm::begin([
			'action' => (isset($originAction) ? [$originAction] : ['index']),
			'method' => 'get',
			'options' => [
				'class' => 'default-form',
			],
		]);

		echo Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel);
	?>

		<div class="col-md-4"> 
			<?= 
				$form->field($model, 'title')->textInput([
					'placeholder' => 'Cerca per ' . Module::t('amosdataset', '#title')
				])->label(Module::t('amosdataset', '#title'))
			?>
		</div>

		<div class="col-md-4"> 
			<?= 
				$form->field($model, 'agid_dataset_type_id')->widget(\kartik\select2\Select2::className(),[
					'data' => ArrayHelper::map(\open20\agid\dataset\models\AgidDatasetType::find()->asArray()->all(), 'id', 'name'),
					'options' => [
						'placeholder' => Module::t('amosdataset', 'Seleziona...'),
						'multiple' => false,
					],
				])->label(Module::t('amosdataset', '#type_of_dataset'))
			?>
		</div>
		
        <div class="col-12 col-md-4">
            <?= 
                $form->field($model, 'status')->widget(Select::className(), [
                    'data' => $model->getAllWorkflowStatus(),

                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosdataset', '#select_choose') . '...',
                        'value' =>  $model->status = null ?? \Yii::$app->request->get(end(explode("\\", $model::className())))['status']
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); 
            ?>
        </div>

	<div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Module::t('amosdataset', '#cancel'), [''], ['class' => 'btn btn-outline-primary']) ?>
            <?= Html::submitButton(Module::t('amosdataset', '#search_for'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end();?>
</div>
