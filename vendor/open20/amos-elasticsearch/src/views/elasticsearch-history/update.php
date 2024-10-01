<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-elasticsearch/src/views 
 */
/**
* @var yii\web\View $this
* @var open20\elasticsearch\models\ElasticsearchHistory $model
*/

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Elasticsearch History',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/elasticsearch']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Elasticsearch History'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="elasticsearch-history-update">

    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
