<?php

use preference\userprofile\models\PreferenceChannel;
use preference\userprofile\utility\UserInterestTagUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$channelEmail = UserInterestTagUtility::isSetUserChannel($topic, Yii::$app->user->id, PreferenceChannel::NEWSLETTER_ID);
$channelSms = UserInterestTagUtility::isSetUserChannel($topic, Yii::$app->user->id, PreferenceChannel::SMS_ID);
$channelApp = UserInterestTagUtility::isSetUserChannel($topic, Yii::$app->user->id, PreferenceChannel::APP_ID);

$noChannelActivate = true;
if ($channelEmail || $channelSms || $channelApp) {
    $noChannelActivate = false;
}
?>
<div>
    <?php
    if (!$noChannelActivate):
    ?>
    <div><small class="text-secondary">Canali di contatto attivi</small></div>
    <div class="d-flex">
        <?php
        if ($channelEmail) {
            echo '<div class="btn-icon d-block mr-3"><span class="rounded-icon border border-secondary rounded-circle mx-auto p-1"><svg class="icon icon-secondary" role="img" aria-label="Icona per gestire la preferenza">
                <use xlink:href="' . $bootstrapItaliaAsset->baseUrl . '/sprite/material-sprite.svg#email"></use>
            </svg></span><small class="text-secondary mx-auto">Email</small></div>';
            $noChannelActivate = false;
        }

        if ($channelSms) {
            echo '<div class="btn-icon d-block mr-3"><span class="rounded-icon border border-secondary rounded-circle mx-auto p-1"><svg class="icon icon-secondary" role="img" aria-label="Icona per gestire la preferenza">
                <use xlink:href="' . $bootstrapItaliaAsset->baseUrl . '/sprite/material-sprite.svg#message-text"></use>
            </svg></span><small class="text-secondary mx-auto">Sms</small></div>';
            $noChannelActivate = false;
        }

        if ($channelApp) {
            echo '<div class="btn-icon d-block mr-3"><span class="rounded-icon border border-secondary rounded-circle mx-auto p-1"><svg class="icon icon-secondary" role="img" aria-label="Icona per gestire la preferenza">
                <use xlink:href="' . $bootstrapItaliaAsset->baseUrl . '/sprite/material-sprite.svg#cellphone"></use>
            </svg></span><small class="text-secondary mx-auto">App</small></div>';
            $noChannelActivate = false;
        }
        ?>
    </div>
    <?php
    endif;
    if ($noChannelActivate):
        ?>

        <div><small class="text-secondary">Nessun canale attivo</small></div>
        <div class="d-flex">
            
               <div class="btn-icon d-block mr-3">
                   <span class="rounded-icon border-secondary border-dotted rounded-circle p-1"></span>
                   <small class="text-white">Nessun canale attivo</small>
                </div>
            
        </div>

        <?php
    endif;
    ?>
</div>