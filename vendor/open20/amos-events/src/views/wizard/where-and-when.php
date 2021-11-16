<?php

use open20\amos\events\AmosEvents;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\events\assets\WizardEventAsset;
use yii\bootstrap4\ActiveForm;
//use open20\amos\core\forms\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = AmosEvents::t('amosevents', 'Crea un nuovo evento');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

/** @var \open20\amos\events\models\search\EventTypeSearch $eventTypeSearchModel */
$eventTypeSearchModel = $moduleEvents->createModel('EventTypeSearch');
$types = $eventTypeSearchModel::searchGenericContextEventTypes()->all();
$moduleCwh = \Yii::$app->getModule('cwh');
$scope = null;
if (!empty($moduleCwh)) {
    $scope = $moduleCwh->getCwhScope();
}


//pr($model->getErrors());

?>
    <div class="heading border-bottom mb-4 pb-2">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="mb-0 pr-2 text-muted"><?= (empty(trim($this->title)) ?: $this->title) ?></h3>
            </div>
            <div>
                <div class="step">2</div>
            </div>

        </div>
    </div>
<?php $form = ActiveForm::begin(); ?>

<?= $this->render('@vendor/open20/amos-events/src/views/event-dashboard/_parts/_where_and_when',[
    'model' => $model,
    'moduleEvents' => $moduleEvents,
    'locations' => $locations,
    'locationsForRadio' => $locationsForRadio,
    'form' => $form,
    'wizardAsset' => $wizardAsset,
    'spriteAsset' => $spriteAsset,
    'eventLocation' => $eventLocation,
    'eventLocationEntrance' => $eventLocationEntrance,
])?>

    <div class="buttons row">
        <?= WizardPrevAndContinueButtonWidget::widget([
            'model' => $model,
            'viewPreviousBtn' => true,
            'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/events/wizard/base-info', 'id' => $model->id]),

            'cancelUrl' => '',

        ]) ?>

    </div>
<?php ActiveForm::end(); ?>