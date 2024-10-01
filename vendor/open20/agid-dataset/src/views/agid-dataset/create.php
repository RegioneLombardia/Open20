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
* @var app\models\AgidDataset $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Agid Dataset',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/app']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Agid Dataset'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agid-dataset-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
