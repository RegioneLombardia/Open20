<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var open20\amos\admin\models\TokenGroup $model
*/

$this->title = Yii::t('cruds', 'Aggiorna {modelClass}', [
    'modelClass' => 'Token Group',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Token Group'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Aggiorna');
?>
<div class="token-group-update">

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider
    ]) ?>

</div>
