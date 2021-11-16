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

$this->title = AmosEvents::t('amosevents', 'Crea un nuovo evento');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

$wizardAsset = WizardEventAsset::register($this);

?>

<div class="heading border-bottom mb-4 pb-2">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="mb-0 pr-2 text-muted"><?= (empty(trim($this->title)) ?: $this->title) ?></h3>
        </div>
        <div>
            <div class="step">3</div>
        </div>

    </div>
</div>
    <?= $this->render('@vendor/open20/amos-events/src/views/event-dashboard/_parts/_invite', [
        'modelSearch' => $modelSearch,
        'internalList' => $internalList,
        'moduleEvents' => $moduleEvents,
        'model' => $model,
        'wizard' => true,
    ]) ?>
