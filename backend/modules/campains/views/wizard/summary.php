<?php

use backend\modules\campains\models\base\PreferenceCampainSection;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use backend\modules\campains\Module;
use open20\amos\core\icons\AmosIcons;
use backend\modules\campains\utility\CampainsUtility;
use backend\modules\campains\models\PreferenceCampain;
use backend\modules\campains\utility\CampainsChannelUtility;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\helpers\VarDumper;

// $this->title                   = Yii::t('campains', 'Nuova Campagna: destinatari e canali');
// $this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
// $this->params['breadcrumbs'][] = $this->title;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

?>


    <div class="heading border-bottom mb-4 pb-2">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="mb-0 pr-2 text-muted text-uppercase">
                    <?= "Nuovo campagna: riepilogo" ?>
                </h3>
            </div>
            <div>
                <div class="step">7</div>
            </div>
        </div>
    </div>


    <div class="newsletter-content">

        <?php 
            $form = ActiveForm::begin([
                'id' => 'newsletter',
                'options' => [
                    'class' => 'needs-validation'
                ]
            ]); 
        ?>  

        <!-- FORM CONTENT -->
        <?= 
            $this->render('_parts/_form_summary', [
                'model' => $model,
                'form' => $form,
                'newsletter_content' => $newsletter_content
            ])
        ?>

        <?php ActiveForm::end(); ?>

    </div>