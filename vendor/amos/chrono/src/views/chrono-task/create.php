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
* @var amos\userimporter\models\UserImportTask $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'User Import Task',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/userimporter']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'User Import Task'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-import-task-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
