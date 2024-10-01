<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/amos/podcast/src/views
 */
/**
 * @var yii\web\View $this
 * @var amos\podcast\models\Podcast $model
 */

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Podcast',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/podcast']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Podcast'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="podcast-update">

    <?= $this->render('_form', [
        'dataProvider' => $dataProvider,
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>
