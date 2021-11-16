<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modues/operators/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\agid\person\Module;

/**
* @var yii\web\View $this
* @var backend\modules\operators\models\AgidPerson $model
*/

$this->title = strip_tags($model);
// $this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/operators']];
// $this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Persona'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;

$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agid-person-view">

    <?= 
        DetailView::widget([
            'model' => $model,    
            'attributes' => [
                'agid_person_content_type_id' => [
                    'attribute' => 'agid_person_content_type_id',
                    'value' => function($model){
                        return $model->agidPersonContentType->name;
                    },
                    'label' => Module::t('amosperson', 'Tipologia content type')
                ],

                'agid_person_type_id' => [
                    'attribute' => 'agid_person_cagid_person_type_idontent_type_id',
                    'value' => function($model){
                        return $model->agidPersonType->name;
                    },
                    'label' => Module::t('amosperson', 'Tipologia persona')
                ],
                'agid_document_cv_id',
                'agid_document_import_id',
                'agid_document_other_posts_id',
                'agid_document_nomination_id',
                'agid_document_balance_sheet_id',
                'agid_document_tax_return_id',
                'agid_document_election_expenses_id',
                'agid_document_changes_balance_sheet_id',
                'name',
                'surname',
                'role',
                'role_description:html',
                'date_end_assignment',
                'skills:html',
                'delegation:html',
                'date_start_settlement',
                'bio:html',
                'telephone',
                'email',
                'other_info:html',
                'status' => [
                    'attribute' => 'status',
                    'value' => function($model){
                        return $model->getWorkflowStatus()->getLabel();
                    },
                    'label' => "Stato"
                ]
            ],    
        ]) 
    ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>
</div>
