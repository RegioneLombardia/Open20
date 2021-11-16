<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modues/operators/views 
 */
/**
* @var yii\web\View $this
* @var backend\modules\operators\models\AgidPersonContentType $model
*/

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Agid Person Content Type',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/operators']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Content Type Persona'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="agid-person-content-type-update">

    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
