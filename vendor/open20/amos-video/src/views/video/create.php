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

$this->title = 'Crea';
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/video']];
$this->params['breadcrumbs'][] = ['label' => 'Video', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="formazione-create">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>
</div>
