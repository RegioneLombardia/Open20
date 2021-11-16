<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\service\views\agid-service
 * @category   CategoryName
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use open20\agid\service\Module;

/**
 * @var yii\web\View $this
 * @var open20\agid\service\models\AgidService $model
 */

$this->title = strip_tags($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="agid-service-view">
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'agid_content_type_service_id' => [
                'attribute' => "agid_content_type_service_id",
                'value' => function($model){
                    return $model->agidServiceContentType->name;
                },
                'label' =>  Module::t('amosservice', '#content_type_typology')
            ],
            'agid_service_type_id' => [
                'attribute' => "agid_service_type_id",
                'value' => function($model){
                    return $model->agidServiceType->name;
                },
                'label' =>  Module::t('amosservice', '#service_type')
            ],
            'agid_service_status_id' => [
                'attribute' => "agid_service_status_id",
                'value' => function($model){
                    return $model->agidServiceStatus->name;
                },
                'label' => Module::t('amosservice', '#service_status')
            ],
            'agid_uo_manager_id' => [
                'attribute' => "agid_uo_manager_id",
                'value' => function($model){
                    return $model->agidUoManager->name;
                },
                'label' => Module::t('amosservice', 'Uff. Responsabile')
            ],
            'agid_uo_area_id' => [
                'attribute' => "agid_uo_area_id",
                'value' => function($model){
                    return $model->agidUoArea->name;
                },
                'label' => Module::t('amosservice', 'Area')
            ],
            'name',
            'service_status_motivation:html',
            'subtitle:html',
            'description:html',
            'long_description:html',
            'recipients_description:html',
            'persons_apply:html',
            'geographical_apply:html',
            'procedure_apply:html',
            'output:html',
            'outcome_procedure_apply:html',
            'digital_channel_url:html',
            'authentication_way:html',
            'physical_channel:html',
            'physical_channel_reservation:html',
            'instructions:html',
            'costs:html',
            'constrains:html',
            'phases_deadline:html',
            'special_case:html',
            'external_links:html',
            'status' => [
                'attribute' => 'status',
                'value' => function($model){
                    return $model->getWorkflowStatus()->getLabel();
                },
                'label' => "Stato"
            ]
        ],
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-primary']); ?>
</div>
