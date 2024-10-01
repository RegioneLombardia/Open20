<?php

use open20\amos\core\helpers\Html;

/**
* @var yii\web\View $this
* @var open20\amos\videoconference\models\Videoconf $model
*/

$this->title = Yii::t('cruds', 'Crea', [
    'modelClass' => 'Videoconf',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Videoconf'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="videoconf-create">
    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
