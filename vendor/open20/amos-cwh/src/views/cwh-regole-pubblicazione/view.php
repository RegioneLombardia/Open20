<?php

use open20\amos\cwh\AmosCwh;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\cwh\models\CwhRegolePubblicazione $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => AmosCwh::t('amoscwh', 'Regole Pubblicazione'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cwh-regole-pubblicazione-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome',
            'codice',
        ],
    ]) ?>

</div>
