<?php

use open20\amos\core\helpers\Html;

/**
* @var yii\web\View $this
* @var open20\amos\admin\models\TokenGroup $model
*/

$this->title = Yii::t('cruds', 'Create {modelClass}', [
    'modelClass' => 'Token Group',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Token Group'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="token-group-create">
    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
