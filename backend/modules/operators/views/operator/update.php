<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/operators/views 
 */
/**
* @var yii\web\View $this
* @var backend\modules\operators\models\Operator $model
*/
use open20\amos\core\helpers\Html;
//$this->title = Yii::t('amoscore', $model->nomeCognome.' Username: '.$model->user->username, [
$this->title = Yii::t('amoscore', $model->nomeCognome, [
    'modelClass' => 'Operator',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/operators']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Operator'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $model->nomeCognome;
?>
<div class="prova">
</div>
<div class="operator-update">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    'structure' => !empty($structure) ? $structure : NULL,
    ]) ?>

</div>
