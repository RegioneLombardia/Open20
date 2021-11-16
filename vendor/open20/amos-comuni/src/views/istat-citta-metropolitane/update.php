<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var open20\amos\comuni\models\IstatCittaMetropolitane $model
 */

$this->title = 'Aggiorna Istat Citta Metropolitane';
$this->params['breadcrumbs'][] = ['label' => 'Istat Citta Metropolitane', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Aggiorna';
?>
<div class="istat-citta-metropolitane-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
