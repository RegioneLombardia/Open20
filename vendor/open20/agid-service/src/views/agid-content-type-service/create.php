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
* @var open20\agid\service\models\AgidServiceContentType $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Agid Content Type Service',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/operators']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Content Type Service'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agid-content-type-service-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
