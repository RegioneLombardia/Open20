<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/operator/views 
 */
/**
* @var yii\web\View $this
* @var open20\agid\service\models\AgidServiceType $model
*/

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Agid Service Type',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/service']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Service Type'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="agid-service-type-update">

    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
