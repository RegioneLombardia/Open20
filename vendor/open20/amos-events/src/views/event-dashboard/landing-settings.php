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
$this->title = AmosEvents::t('amosevents', 'Configura landing');
$this->params['breadcrumbs'][] = $this->title;

$wizardAsset = \open20\amos\events\assets\WizardEventAsset::register($this);

?>

<?php $form = ActiveForm::begin([

    'options' => ['class' => 'needs-validation']
]); ?>

<?= $form->errorSummary($eventLanding) ?>

<?= $this->render('@vendor/open20/amos-events/src/views/event-dashboard/_parts/_landing', [
    'model' => $model,
    'moduleEvents' => $moduleEvents,
    'arrayTemplates' => $arrayTemplates,
    'htmlTemplate' => $htmlTemplate,
    'eventLanding' => $eventLanding,
    'form' => $form
]); ?>

    <div class="buttons d-flex">
        <?php echo Html::submitButton(AmosEvents::t('amosevents', 'Salva'), ['class' => 'btn btn-primary ml-auto']) ?>

    </div>

<?php ActiveForm::end(); ?>