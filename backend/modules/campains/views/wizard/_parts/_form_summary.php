<?php

use yii\helpers\Html;
use yii\bootstrap\Button;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;
use yii\bootstrap4\ActiveForm;
use backend\modules\campains\Module;
use open20\amos\core\icons\AmosIcons;
use backend\modules\campains\utility\CampainsUtility;
use backend\modules\campains\models\PreferenceCampain;
use open20\amos\events\assets\WizardEventAsset;
use backend\modules\campains\utility\CampainsQueryUtility;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\core\utilities\WorkflowTransitionWidgetUtility;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

?>

<div class="row mt-5">
    <div class="col-md-3 text-uppercase">
        <p class="h4 mb-5">
            Hai creato con successo la campagna!
        </p>
    </div>
    <div class="ml-auto col-md-8">
        <div class="d-flex align-items-center mb-4">
            <div>
                <svg class="icon icon-primary icon-lg mr-2">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_check_circle"></use>
                </svg>
            </div>
            <span>
                La campagna si chiama: <strong><?= $model->titolo ?></strong><br>

                Creata da:
                <strong>
                    <?php 
                        $user_profile = open20\amos\admin\models\UserProfile::find()->where(['user_id' => $model->created_by])->one();
                        echo $user_profile->nome." ".$user_profile->cognome;
                    ?>
                </strong>

                <br>Struttura: 
                <strong>
                    <?= $struttura = $model->structure->name ?>
                </strong>

                <br>Data creazione:
                <strong>
                    <?= $model->created_at ?>
                </strong>

                <br>Stato:
                <strong>
                    <?= WorkflowTransitionWidgetUtility::getLabelStatus($model) ?>
                </strong>

                <br>Verrà spedita attraverso i canali:
                <strong>
                    <?php
                        $channels = [];
                        if (CampainsUtility::existNewsletterChannel($model)) {
                            $channels[] = "Email";
                        }
                        if (CampainsUtility::existSmsChannel($model)) {
                            $channels[] =  "Sms";
                        }
                        if (CampainsUtility::existAppChannel($model)) {
                            $channels[] =  "App";
                        }
                        echo implode(', ', $channels);
                    ?>
                </strong>
   
            </span>
        </div>

        <?php if (CampainsUtility::existNewsletterChannel($model)) : ?>
            <div class="d-flex align-items-center mb-4">
                <div>
                <svg class="icon icon-primary icon-lg mr-2">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_check_circle"></use>
                </svg>
                </div>
                <span>
                    La Newsletter sarà inviata a <strong><?= CampainsQueryUtility::getQueryRecipientsByChannelModel(CampainsUtility::campainGetNewsletterModel($model))->count() ?></strong>
                </span>
            </div>
        <?php endif; ?>
        <?php if (CampainsUtility::existSmsChannel($model)) : ?>
            
            <div class="d-flex align-items-center mb-4">
                <div>
                <svg class="icon icon-primary icon-lg mr-2">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_check_circle"></use>
                </svg>
                </div>
                <span>

                    I messaggi SMS saranno inviati a <strong><?= CampainsQueryUtility::getQueryRecipientsByChannelModel(CampainsUtility::campainGetSmsModel($model))->count() ?></strong>
                    </span>
            </div>
        <?php endif; ?>
        <?php if (CampainsUtility::existAppChannel($model)) : ?>
            
            <div class="d-flex align-items-center mb-4">
                <div>
                <svg class="icon icon-primary icon-lg mr-2">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_check_circle"></use>
                </svg>
                </div>
                <span>

                    I contenuti App saranno visibili a <strong><?= CampainsQueryUtility::getQueryRecipientsByChannelModel(CampainsUtility::campainGetAppModel($model))->count() ?></strong>
                    </span>
            </div>
        <?php endif; ?>
        <div class="mt-5 pt-5">
            <p>
                <strong>GRAZIE!</strong><br>
                Nel caso tu voglia modificare alcune opzioni o personalizzare ulteriormente i dettagli della campagna
                puoi tornare alla DASHBOARD altrimenti procedi con l'invio della campagna.
            </p>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="offset-md-4 col-md-8">
        <?= Html::a('Torna alla Dashboard', ['/campains/preference-campain/index'], ['class' => 'btn btn-primary mb-2']) ?>
        <?= Html::a("Prosegui con l'invio", ['/campains/preference-campain/update-sending-tracking?id=' . $model->id], ['class' => 'btn btn-primary mb-2']) ?>
    </div>
</div>
