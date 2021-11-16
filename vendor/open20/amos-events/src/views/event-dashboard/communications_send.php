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
$this->title = AmosEvents::t('amosevents', 'Invia Comunicazione');
$this->params['breadcrumbs'][] = $this->title;

$wizardAsset = \open20\amos\events\assets\WizardEventAsset::register($this);

?>
<h4><?= AmosEvents::t('amosevents', 'Invia Comunicazione "{title}" per l\'evento "{event}"',[
        'title' => $eventCommunication->title,
        'event' => $model->title
    ]) ?> </h4>

<p><?= AmosEvents::t('amosevent', 'Sono stati trovati <strong>{n}</strong> utenti', ['n' => $n_users]) ?></p>

<hr class="dashed">

<p><?= AmosEvents::t('amosevent', 'Stai per inviare la seguente comunicazione').':' ?></p>

<?php $form = ActiveForm::begin([
    'action' => '/events/event-dashboard/send-communication?idCommunication=' . $eventCommunication->id.'&eid='.$model->id,
    'options' => ['class' => 'needs-validation',]
]); ?>

<div class="row">
    <div class="col-md-3">
        <p>
            <strong><?= AmosEvents::t('amosevents', 'Oggetto comunicazione') .': '?></strong>
        </p>
    </div>
    <div class="col-md-9">
        <?= $eventCommunication->subject_email  ?>
    </div>
</div>


<div class="row">
    <div class="col-md-3">
        <p>
            <strong><?=  AmosEvents::t('amosevents', 'Testo comunicazione').': ' ?></strong>
        </p>
    </div>
    <div class="col-md-9">
        <p><?=$eventCommunication->text_email ?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= \yii\helpers\Html::hiddenInput('send_com') ?>
    </div>
</div>
<div class="mt-5 d-flex">

<?php
echo Html::a(AmosEvents::t('amosevents', 'Back'), [
    '/events/event-dashboard/communications', 'id' => $model->id
],
    [
        'class' => 'btn btn-secondary mr-auto'
    ]);
echo Html::submitButton(AmosEvents::t('amosevents', 'Invia Comunicazione'), [
    'data-confirm' => 'Sei sicuro di inviare la comunicazione?',
    'class' => 'btn btn-primary'
])
?>
</div>
<?php ActiveForm::end(); ?>
