<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\service\views\agid-service
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use open20\agid\service\Module;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\agid\service\models\AgidServiceContentType;
use open20\agid\service\models\AgidServiceType;
use open20\agid\service\models\AgidService;
use open20\agid\service\models\AgidServiceStatus;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;
use open20\amos\admin\models\UserProfile;
use kartik\datecontrol\DateControl;


/**
 * @var yii\web\View $this
 * @var open20\agid\service\models\search\AgidServiceSearch $model
 * @var yii\widgets\ActiveForm $form
 */

 
// enable open search modal 
$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;

?>
<div class="agid-service-search element-to-toggle" data-toggle-element="form-search">
    
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
            <?=
                $form->field($model, 'name')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Titolo')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?= 
                $form->field($model, 'agid_content_type_service_id')->widget(Select::className(), [
                    'data' => ArrayHelper::map(AgidServiceContentType::find()->orderBy('name')->all(), 'id', 'name'),
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

        <div class="col-md-4"> 
            <?= 
                $form->field($model, 'agid_service_status_id')->widget(Select::className(), [
                    'data' => ArrayHelper::map(AgidServiceStatus::find()->orderBy('name')->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosservice', '#select_choose') . '...'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]); 
            ?>
        </div>

        <div class="col-md-4"> 
            <?= 
                $form->field($model, 'service_status_motivation')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Motivo dello stato')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?= 
                $form->field($model, 'subtitle')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Titolo alternativo/Sottotitolo')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'description')
                    ->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', '#synthetic_description')])
                    ->label(Module::t('amosservice', '#synthetic_description'));
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'long_description')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', '#long_description')]) 
            ?>
        </div>
                            
        <div class="col-md-4"> 
            <?=
                $form->field($model, 'recipients_description')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', '#recipients_description')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'persons_apply')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Chi può presentare')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'geographical_apply')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Copertura geografica')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'procedure_apply')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Come si fa')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'output')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Output/Cosa si ottiene')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'outcome_procedure_apply')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Procedure collegate all\'esito')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'digital_channel_url')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Canale digitale')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'authentication_way')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Autenticazione')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'physical_channel')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Canale fisico')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'physical_channel_reservation')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Canale fisico - prenotazione')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'instructions')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Cosa serve (istruzioni per partecipare al servizio)')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'costs')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Costi')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'constrains')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Vincoli')]) 
            ?>
        </div>
        
        <div class="col-md-4"> 
            <?=
                $form->field($model, 'phases_deadline')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Fasi e scadenze')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'special_case')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Casi particolari')]) 
            ?>
        </div>

        <div class="col-md-4"> 
            <?=
                $form->field($model, 'external_links')->textInput(['placeholder' => 'Cerca per ' . Module::t('amosservice', 'Link a siti esterni')]) 
            ?>
        </div>
        
        <!-- Correlati: servizi -->
        <div class="col-md-4"> 
            <?= 
                $form->field($model, 'agid_service_related_service_mm')->widget(Select::className(), [
                    'data' => ArrayHelper::map(AgidService::find()->andWhere(['deleted_at' => null])->orderBy('name')->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosservice', '#select_choose') . '...',
                        'value' => \Yii::$app->request->get('AgidServiceSearch')['agid_service_related_service_mm']
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); 
            ?>
        </div>

        <div class="col-md-4"> 
            <?= 
                $form->field($model, 'agid_uo_manager_id')->widget(Select::className(), [
                    'data' => ArrayHelper::map(AgidOrganizationalUnit::find()->orderBy('name')->all(), 'id', 'name'),
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

        <div class="col-md-4"> 
            <?= 
                $form->field($model, 'agid_uo_area_id')->widget(Select::className(), [
                    'data' => ArrayHelper::map(AgidOrganizationalUnit::find()->orderBy('name')->all(), 'id', 'name'),
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

        <div class="col-md-4"> 
            <?= 
                $form->field($model, 'agid_service_type_id')->widget(Select::className(), [
                    'data' => ArrayHelper::map(AgidServiceType::find()->orderBy('name')->all(), 'id', 'name'),
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

        <div class="col-12 col-md-4">
            <?= 
                $form->field($model, 'updated_by')->widget(Select::className(), [
                    'data' => ArrayHelper::map(UserProfile::find()->andWhere(['deleted_at' => NULL])->all(), 'user_id', function($model) {
                        return $model->nome . " " . $model->cognome;
                    }),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosservice', '#select_choose') . '...'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(Module::t('amosservice', '#updated_by')); 
            ?>
        </div>

        <div class="col-12 col-md-4">
            <?= 
                $form->field($model, 'updated_from')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                    'value' => $model->updated_from = \Yii::$app->request->get(end(explode("\\", $model::className())))['updated_from'],
                ])->label(Module::t('amosservice', '#updated_from')); 
            ?>
        </div>

        <div class="col-12 col-md-4">
            <?= 
                $form->field($model, 'updated_to')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                    'value' => $model->updated_to = \Yii::$app->request->get(end(explode("\\", $model::className())))['updated_to'],
                ])->label(Module::t('amosservice', '#updated_to')); 
            ?>
        </div>

        <div class="col-12 col-md-4">
            <?= 
                $form->field($model, 'status')->widget(Select::className(), [
                    'data' => $model->getAllWorkflowStatus(),

                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosservice', '#select_choose') . '...',
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
			<?= Html::submitButton(Module::t('amosservice', '#search_for'), ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

    <div class="clearfix"></div>
    
    <?php ActiveForm::end(); ?>
</div>
