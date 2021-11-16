<?php

use open20\amos\events\AmosEvents;

$this->title = AmosEvents::t('amosevents', "Cancella l'evento");
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;


?>

<?php if (!empty($step_2)) { ?>
    <h4><?= AmosEvents::t("amosevents", "Sei davvero sicuro di cancellare l'evento?") ?></h4>
<?php } else { ?>
    <h4 class="mb-5"><?= AmosEvents::t("amosevents", "Cliccando conferma eliminerai definitivamente l'evento e tutti i suoi dati. <br>Sei sicuro di cancellare l'evento?") ?></h4>
<?php } ?>
<?php
\yii\bootstrap4\ActiveForm::begin();

echo \yii\helpers\Html::a(AmosEvents::t("amosevents", 'Annulla'), ['/events/event-dashboard/view', 'id' => $model->id], [
    'class' => 'btn btn-secondary mr-3'
]);
echo \yii\helpers\Html::submitButton(AmosEvents::t("amosevents", 'Conferma'), [
    'data-confirm' => AmosEvents::t("amosevents", "Sei sicuro di cancellare l'evento?"),
    'class' => 'btn btn-primary'
]);
if (!empty($step_2)) {
    echo \yii\helpers\Html::hiddenInput('step_2', 1);
}
else {
    echo \yii\helpers\Html::hiddenInput('step_1', 1);
}

\yii\bootstrap4\ActiveForm::end();
?>
