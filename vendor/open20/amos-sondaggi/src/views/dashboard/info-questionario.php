<?php

use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\events\assets\WizardEventAsset;
use yii\bootstrap4\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = AmosSondaggi::t('amossondaggi', "Info sondaggio");
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['sondaggi/manage']];
$this->params['breadcrumbs'][] = ['label' => $model->titolo, 'url' => ['sondaggi/dashboard', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];

 echo $this->render('@vendor/open20/amos-sondaggi/src/views/sondaggi/update', [
        'model' => $model,
        'public' => isset($public) ? $public : null,
        'moduleCwh' => $moduleCwh,
        'scope' => $scope
    ]);
