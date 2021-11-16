<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
* @var yii\web\View $this
* @var backend\modules\userimporter\models\UserImportTask $model
*/

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/userimporter']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'User Import Task'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-import-task-view">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
                'name',
            [
                'attribute'=>'task_date',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],                
            ],
            'user_id',
            'event_group_referent_id',
            'tot_input_processed',
            'tot_input_imported',
            'file_input',
            'file_success_input',
            'file_errors_input',
    ],    
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
