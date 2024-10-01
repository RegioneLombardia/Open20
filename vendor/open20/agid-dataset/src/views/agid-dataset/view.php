<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\agid\dataset\Module;

/**
* @var yii\web\View $this
* @var app\models\AgidDataset $model
*/

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/app']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Agid Dataset'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agid-dataset-view">

    <?= 
        DetailView::widget([
            'model' => $model,    
            'attributes' => [

                'agid_dataset_content_type_id' => [
                    'attribute' => "agid_dataset_content_type_id",
                    'label' => Module::t('amosdataset', '#agid_dataset_content_type_id'),
                    'value' => $model->agidDatasetContentType->name,
                ],

                'agid_dataset_type_id' => [
                    'attribute' => 'agid_dataset_type_id',
                    'label' => Module::t('amosdataset', '#agid_dataset_type_id'),
                    'value' => $model->agidDatasetType->name,
                ],

                'title' => [
                    'attribute' => 'title',
                    'label' => Module::t('amosdataset', '#title'),
                ],

                'description:html' => [
                    'attribute' => "description",
                    'label' => Module::t('amosdataset', '#description'),
                ],
                'status' => [
                    'attribute' => 'status',
                    'value' => function($model){
                        return $model->getWorkflowStatus()->getLabel();
                    },
                    'label' => Module::t('amosdataset', '#status'),
                ]
            ],    
        ]) 
    ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>
</div>
