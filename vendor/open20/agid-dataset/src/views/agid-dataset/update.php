<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views 
 */
/**
* @var yii\web\View $this
* @var app\models\AgidDataset $model
*/

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Agid Dataset',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/app']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Agid Dataset'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="agid-dataset-update">

    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
