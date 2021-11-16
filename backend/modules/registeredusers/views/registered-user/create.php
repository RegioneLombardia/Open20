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

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Operator',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/registeredusers']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Operator'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registereduser-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
	'structure' => !empty($structure) ? $structure : NULL,
    ]) ?>

</div>
