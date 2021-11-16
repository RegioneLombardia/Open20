<?php

use open20\amos\events\AmosEvents;

$this->title = AmosEvents::t('amosevents', "Permesso negato",[
    'event' => $model->title
]);
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;



?>

<h4><?= AmosEvents::t("amosevents", "Permesso negato") ?></h4>

<h5><?= AmosEvents::t("amosevents", "L'utente corrente non è associato a nessuna Dg") ?></h5>
<?php
echo \yii\helpers\Html::a(AmosEvents::t("amosevents", 'Chiudi'),'/dashboard', [
    'class' => 'btn btn-secondary'
]);
?>

