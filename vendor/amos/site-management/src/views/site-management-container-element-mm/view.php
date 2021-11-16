<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use amos\sitemanagement\Module;


/**
* @var yii\web\View $this
* @var amos\sitemanagement\models\SiteManagementContainerElementMm $model
*/

$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Container Element Mm'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-container-element-mm-view col-xs-12">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
                'id',
            'container_id',
            'element_id',
            [
                'attribute'=>'created_at',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],                
            ],
            [
                'attribute'=>'updated_at',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],                
            ],
            [
                'attribute'=>'deleted_at',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],                
            ],
            'created_by',
            'updated_by',
            'deleted_by',
    ],    
    ]) ?>

    <div class="btnViewContainer pull-right">
        <?= Html::a(Yii::t('amoscore', 'Chiudi'),  (\Yii::$app->request->referrer ?: \Yii::$app->session->get('previousUrl')), ['class' => 'btn btn-secondary']); ?>    </div>

</div>
