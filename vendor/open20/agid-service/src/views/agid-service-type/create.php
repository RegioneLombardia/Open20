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

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Agid Service Type',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/service']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Service Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agid-service-type-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
