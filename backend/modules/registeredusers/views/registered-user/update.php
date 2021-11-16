<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/registeredusers/views
 */
/**
* @var yii\web\View $this
* @var backend\modules\registeredusers\models\Operator $model
*/

$this->title = Yii::t('amoscore', $model->nomeCognome, [
    'modelClass' => 'Operator',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/registeredusers']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Operator'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $model->nomeCognome;
?>
<div class="registereduser-update">

    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    'structure' => !empty($structure) ? $structure : NULL,
    ]) ?>

</div>
