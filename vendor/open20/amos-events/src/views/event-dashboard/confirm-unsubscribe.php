<?php

use open20\amos\events\AmosEvents;

$this->title = AmosEvents::t('amosevents', "Disiscriviti dall'evento '{event}'",[
    'event' => $model->title
]);
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;



?>

<h4><?= AmosEvents::t("amosevents", "Sei davvero sicuro di disiscriverti dall'evento?") ?></h4>


<?php
echo \yii\helpers\Html::a(AmosEvents::t("amosevents", 'Disiscriviti'),['/events/event-dashboard/unsubscribe', 'id' => $model->id], [
    'data-confirm' => AmosEvents::t("amosevents", "Sei sicuro di disiscriverti dall'evento?"),
    'class' => 'btn btn-primary'
]);
?>

