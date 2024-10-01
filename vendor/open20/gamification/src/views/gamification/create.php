<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var open20\gamification\models\Gamification $model */
/* @var \open20\amos\core\user\User $data */

$this->params['breadcrumbs'][] = ['label' => Yii::t('gamification', 'Gamifications'), 'url' => ['index']];
$this->title =  \Yii::t('gamification', 'Crea gamification');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gamification">

    <?= $this->render('_form', [
        'model' => $model,
        'data' => $data,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>
