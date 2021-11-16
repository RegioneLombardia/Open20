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

$this->title = Yii::t('operators', 'Crea operatore', [
    'modelClass' => 'Operator',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/operators']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Operator'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operator-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
	'structure' => !empty($structure) ? $structure : NULL,
    ]) ?>

</div>
