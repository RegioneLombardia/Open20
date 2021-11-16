<?php
use open20\amos\events\AmosEvents;
$wizardAsset = \open20\amos\events\assets\WizardEventAsset::register($this);

?>
<h4><?= AmosEvents::t('amosevents', "Inserisci l’indirizzo dell’evento")?></h4>
<?= $form->field($eventLocation, 'name')->label(AmosEvents::t('amosevents', 'Luogo di Svolgimento'))->textInput(['placeholder' =>  AmosEvents::t('amosevents', 'Inserisci il luogo di svolgimento'),'maxlength' => true]) ?><!-- description string -->
<?= $form->field($eventLocation, 'description')->textarea(['placeholder' =>  AmosEvents::t('amosevents', 'Inserisci una descrizione del luogo di svolgimento. Usa al massimo 100 caratteri.'),'maxlength' => true]) ?><!-- event_place_id string -->
<?= $form->field($eventLocation, 'event_place_id')->label(AmosEvents::t('amosevents', 'Indirizzo'))->widget(
    \open20\amos\events\widgets\maps\PlaceWidget::className(), [
        'placeAlias' => 'eventPlaces'
    ]
); ?>

<h4><?= AmosEvents::t('amosevents', "Dettagli dell'indirizzo")?></h4>
<?= $form->field($eventLocationEntrance, 'name')->label(AmosEvents::t('amosevents', 'Informazioni aggiuntive'))->textInput(['placeholder' =>  AmosEvents::t('amosevents', 'Inserisci informazioni su entrata, nome della sala, etc.'),'maxlength' => true]) ?><!-- description string -->
