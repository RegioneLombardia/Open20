<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var open20\gamification\models\Gamification $model */
/* @var \open20\amos\core\user\User $data */


$this->title = Yii::t('amoscore', 'Aggiorna Gamification');

$this->params['breadcrumbs'][] = ['label' => Yii::t('gamification', 'Gamifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gamification-update">

    <?= $this->render('_form', [
        'model' => $model,
        'data' => $data,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>