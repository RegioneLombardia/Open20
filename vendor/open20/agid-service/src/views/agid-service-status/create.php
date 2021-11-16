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
* @var open20\agid\service\models\AgidServiceStatus $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Agid Service Status',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/service']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Service Status'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agid-service-status-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
