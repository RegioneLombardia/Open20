<?php

use open20\amos\events\AmosEvents;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use \yii\bootstrap4\ActiveForm;
// use open20\amos\core\forms\ActiveForm;
use open20\amos\events\models\search\UserEventSearch;
use yii\helpers\Html;

$this->title = AmosEvents::t('amosevents', 'Modifica Ricerca utenti');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

$wizardAsset = WizardEventAsset::register($this);

?>


<?= $this->render('@vendor/open20/amos-events/src/views/event-dashboard/_parts/_invite', [
    'modelSearch' => $modelSearch,
    'internalList' => $internalList,
    'moduleEvents' => $moduleEvents,
    'wizard' => false,
    'saveButton' => true,
    'model' => $model
]) ?>
