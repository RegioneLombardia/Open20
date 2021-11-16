<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\events\AmosEvents;
use yii\bootstrap4\ActiveForm;

use yii\web\View;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\events\models\search\EventSearch $model
 * @var string $currentView
 */


/** @var AmosEvents $eventsModule */
$eventsModule = Yii::$app->getModule(AmosEvents::getModuleName());
$this->title = AmosEvents::t('amosevents', 'Gestione Invii');
$this->params['breadcrumbs'][] = $this->title;

$wizardAsset = \open20\amos\events\assets\WizardEventAsset::register($this);
?>

<div class="alert alert-warning" role="alert">
    <p>
        <strong><?= AmosEvents::t('amosevents', "Prima di procedere con l'invio, verifica che il tuo evento sia configurato correttamente e controlla i contenuti visibili sulla landing page") ?></strong>
        <br><?= AmosEvents::t('amosevents', "Verifica la configurazione del tuo evento <a href='{link_info_event}'>qui</a>", [
                'link_info_event' => '/events/event-dashboard/info-event?id=' . $event->id
            ]
        ) ?>
        <br><?= AmosEvents::t('amosevents', "Controlla i contenuti della landing page <a href='{link_manage_content}'>qui</a>", [
            'link_manage_content' => '/events/event-dashboard/landing-manage-contents?id=' . $event->id

        ]) ?>
    </p>
</div>

<h4><?= AmosEvents::t('amosevent', 'Seleziona un modello email da inviare agli utenti della lista <strong>{name}</strong>', ['name' => $internalList->name]) ?></h4>
<p><?= AmosEvents::t('amosevent', 'Nella lista selezionata sono stati trovati <strong>{n}</strong> utenti', ['n' => $count]) ?></p>
<?php $form = ActiveForm::begin([
    'action' => '/events/event-dashboard/send-invitation?idInternalList=' . $internalList->id . '&eid=' . $event->id,
    'options' => ['class' => 'needs-validation',]
]); ?>
<div class="row">
    <div class="col-md-6">
        <?php $types['save_the_date'] = AmosEvents::t('amosevent', 'Save the date'); ?>
        <?php if ($event->eventType->event_type != \open20\amos\events\models\EventType::TYPE_INFORMATIVE) {
            $types['registration_email'] = AmosEvents::t('amosevent', 'invito alla registrazione');
        } ?>
        <?php echo $form->field($invitationSent, 'template')->label(AmosEvents::t('amosevents', 'Modello email predefinito'))->widget(\kartik\select2\Select2::className(), [
            'data' => $types
        ]); ?>
    </div>
</div>

<?php
echo Html::a(AmosEvents::t('amosevents', 'Back'), [
    '/events/event-dashboard/invite', 'id' => $event->id
],
    [
        'class' => 'btn btn-secondary mr-3'
    ]);
echo Html::submitButton(AmosEvents::t('amosevents', 'Invia email agli utenti selezionati'), [
    'data-confirm' => 'Sei sicuro di inviare gli inviti?',
    'class' => 'btn btn-primary'
])
?>
<?php ActiveForm::end(); ?>
