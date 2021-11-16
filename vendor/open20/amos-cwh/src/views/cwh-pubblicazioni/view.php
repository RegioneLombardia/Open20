<?php

use open20\amos\cwh\AmosCwh;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\cwh\models\CwhPubblicazioni $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => AmosCwh::t('amoscwh', 'Cwh Pubblicazioni'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cwh-pubblicazioni-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'cwh_config_id',
            'cwh_regole_pubblicazione_id',
        ],
    ]) ?>

</div>
