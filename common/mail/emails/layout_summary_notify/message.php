<?php

use open20\amos\admin\AmosAdmin;
use yii\helpers\Html;
use frontend\models\PrivacyHistory;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

$appName = Yii::$app->name;

if (isset(Yii::$app->params['layoutMailConfigurations']['bgPrimary'])) {
    $bgPrimary = Yii::$app->params['layoutMailConfigurations']['bgPrimary'];
} else {
    $bgPrimary = $notifyModule->mailThemeColor['bgPrimary'];
}
if (isset(Yii::$app->params['layoutMailConfigurations']['textContrastBgPrimary'])) {
    $textContrastBgPrimary = Yii::$app->params['layoutMailConfigurations']['textContrastBgPrimary'];
} else {
    $textContrastBgPrimary = $notifyModule->mailThemeColor['textContrastBgPrimary'];
}

if (isset(Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImg'])) {
    $logoMail = Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImg']);
} else {
    $logoMail = '';
}

if (isset(Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgAlt'])) {
    $logoAlt = Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgAlt'];
} else {
    $logoAlt = 'logo';
}
if (isset(Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgWidth'])) {
    $logoWidth = Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgWidth'];
} else {
    $logoWidth = "auto";
}
if (isset(Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgHeight'])) {
    $logoHeight = Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgHeight'];
} else {
    $logoHeight = "auto";
}
if (isset(Yii::$app->params['layoutMailConfigurations']['logoMail']['logoText'])) {
    $logoText = Yii::$app->params['layoutMailConfigurations']['logoMail']['logoText'];
} else {
    $logoText = "";
}
?>

<table bgcolor="#F6F6F6" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;">
    <tr>
        <td bgcolor="<?= $textContrastBgPrimary ?>" style="padding:0 15px; border-top:15px solid <?= $bgPrimary ?>">
            <center style="width: 100%;">
                <table cellspacing="0" cellpadding="0" border="0" align="center" width="590" class="email-container">
                    <tr>
                        <td style="padding:15px 0">
                            <img width="<?= $logoWidth ?>" height="<?= $logoHeight ?>" src="<?= $logoMail ?>"
                                 alt="<?= $logoAlt ?>" style="width:<?= $logoWidth ?>px; " border="0">
                            <p style="margin-left:24px"><?= $logoText ?></p>
                        </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
</table>
<table bgcolor="#F6F6F6" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;">
    <tr>
        <td style="padding:20px">
            <center style="width: 100%;">

                <table cellspacing="0" cellpadding="0" border="0" align="center" width="590">
                    <?php echo $contents; ?>
                </table>
            </center>
        </td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;">
    <tr>
        <td bgcolor="<?= $textContrastBgPrimary ?>" style="padding:0 15px;">
            <center style="width: 100%;">
                <table cellspacing="0" cellpadding="0" border="0" align="center" width="590" class="email-container">
                    <tr>
                        <td style="padding:15px 0; text-align:center; font-family:sans-serif; font-size:11px; color:#9C9C9C;">
                            <?php if (!empty($this->params['profile'])) {
                                $profile = $this->params['profile'];
                                $token = PrivacyHistory::getLastUserRecord($profile->user_id)->token;
                            } ?>

                            <?php if (!empty($token)) { ?>
                                <p style="margin: 0px; text-align: center">
                                    <?php
                                    $aTag = Html::a(Yii::t('amosapp', '#click_here'), Yii::$app->urlManager->createAbsoluteUrl('/preferences/unsubscribe?token=' . $token), ['title' => Yii::t('amosapp', '#footer_disable_notification'), 'target' => '_blank']);
                                    echo Yii::t('amosapp', '#unsubscribe', [
                                            'appName' => $appName
                                        ]) . ' ' . $aTag;
                                    ?>
                                </p>
                            <?php } ?>
                            <?php if (!empty($profile)) { ?>
                                <p>
                                    <?= Yii::t('amosapp', 'Per gestire i propri interessi, gestione dell’email ed adesione al servizio, ') ?>
                                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('/preferences/change') ?>"
                                       title="<?= Yii::t('amosapp', '#login_profile') ?>"
                                       target="_blank"><?= Yii::t('amosapp', '#login_profile') ?>
                                    </a>
                                </p>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
</table>