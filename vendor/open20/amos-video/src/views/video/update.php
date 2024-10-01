<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\modules\formazione/views
 */
/**
 * @var yii\web\View $this
 * @var frontend\modules\formazione\models\Formazione $model
 */

$this->title = 'Aggiorna';
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/video']];
$this->params['breadcrumbs'][] = ['label' => 'Video', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Aggiorna';
?>
<div class="formazione-update">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>
</div>
