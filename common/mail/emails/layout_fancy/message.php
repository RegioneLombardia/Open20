<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */


use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\notificationmanager\AmosNotify;


$appLink = Yii::$app->urlManager->createAbsoluteUrl(['/']);
$appName = Yii::$app->name;

try {
    /** @var AmosNotify $notifyModule */
    $notifyModule = AmosNotify::instance();
    $hasMailThemeColor = (!is_null($notifyModule) && $notifyModule->hasProperty('mailThemeColor'));
} catch (\Exception $exception) {
    $hasMailThemeColor = false;
}

if (isset(Yii::$app->params['layoutMailConfigurations']['bgPrimary'])) {
    $bgPrimary = Yii::$app->params['layoutMailConfigurations']['bgPrimary'];
} elseif ($hasMailThemeColor) {
    $bgPrimary = $notifyModule->mailThemeColor['bgPrimary'];
} else {
    $bgPrimary = '#297A38';
}
if (isset(Yii::$app->params['layoutMailConfigurations']['textContrastBgPrimary'])) {
    $textContrastBgPrimary = Yii::$app->params['layoutMailConfigurations']['textContrastBgPrimary'];
} elseif ($hasMailThemeColor) {
    $textContrastBgPrimary = $notifyModule->mailThemeColor['textContrastBgPrimary'];
} else {
    $textContrastBgPrimary = '#ffffff';
}

$privacyLinkRelative = (isset(\Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon']) ?
    \Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'] :
    'site/privacy'
);
$privacyLink = Yii::$app->urlManager->createAbsoluteUrl($privacyLinkRelative);

?>
<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td style="margin-bottom:10px;background-color:<?= $bgPrimary ?>;height:15px"></td>
        <td style="margin-bottom:10px;background-color:<?= $bgPrimary ?>;height:15px"></td>
        <td style="margin-bottom:10px;background-color:<?= $bgPrimary ?>;height:15px"></td>
    </tr>
    <tr>
        <td style="height:10px"></td>
    </tr>
    <tr style="background-color:<?= $textContrastBgPrimary ?>;">
        <td style="display:flex;flex-direction:row;align-items:center;">
            <?php if (isset(Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImg'])) {
                $logoMail = Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImg']);
            } elseif (isset(Yii::$app->params['logoMail'])) {
                $logoMail = $appLink . Yii::$app->params['logoMail'];
            } else {
                $logoMail = '';
            }

            if (isset(Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgAlt'])) {
                $logoAlt = Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgAlt'];
            } else {
                $logoAlt = 'Logo';
            }
            if (isset(Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgWidth'])) {
                $logoWidth = Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgWidth'];
            } else {
                $logoWidth = '420';
            }
            if (isset(Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgHeight'])) {
                $logoHeight = Yii::$app->params['layoutMailConfigurations']['logoMail']['logoImgHeight'];
            } else {
                $logoHeight = 'auto';
            }
            ?>
            <img width="<?= $logoWidth ?>" height="<?= $logoHeight?>" src="<?= $logoMail ?>" alt="<?= $logoAlt ?>">
            <?php if (isset(Yii::$app->params['layoutMailConfigurations']['logoMail']['logoText'])): ?>
                <span style="margin-left:24px;position: relative;top: -2px;font-size: 18px;font-weight: 700;color: #5e7887;"><?= Yii::$app->params['layoutMailConfigurations']['logoMail']['logoText'] ?></span>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td style="height:30px"></td>
    </tr>
</table>

<?php if ($heading) { ?>
    <table width=" 600" border="0" cellpadding="0" cellspacing="0" align="center">
        <tr>
            <td align="center" valign="top">
                <h1 style="padding-top: 25px; color:green;margin:0;display:block;font-family:Arial;font-size:25px;font-weight:bold;text-align:center;line-height:150%"><?php echo $heading; ?></h1>
            </td>
        </tr>
    </table>
<?php } ?>


<table width=" 600" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <div class="corpo"
                 style="padding:10px;margin-bottom:10px;background-color:#fff;">
                <?php echo $contents; ?>
            </div>
        </td>
    </tr>
</table>

<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <!--            <div style="color:black; background-color:lightgrey; padding:10px; font-family:Arial;font-size:12px;line-height:150%;text-align:left">-->
            <div style="font-style: italic; color: #b0b0b0; margin-top:10px;border-top: 2px solid <?= $bgPrimary ?>;padding-top: 5px;font-size: 11px;line-height: normal;">
                <?= Yii::t('amosplatform', '#footer_template_mail', [
                    'appName' => $appName,
                ]) ?>
                <p style="margin: 0px;">
                    <a href="<?= $privacyLink; ?>"
                      title="<?= Yii::t('amosplatform', '#footer_template_mail_privacy_title') ?>"
                      target="_blank"><?= Yii::t('amosplatform', '#footer_template_mail_privacy') ?>
                    </a>
                    <br>
                </p>
                <br>
                <?php if(!empty($this->params['profile'])) {
                    /** @var UserProfile $profile */
                    $profile = $this->params['profile'];
                    $token = md5($profile->user_id . $appName . $profile->user->username);
                }?>

                <?php if(!empty($token)) {
                    $disableNotificationLink = Yii::$app->urlManager->createAbsoluteUrl([
                        '/' . AmosAdmin::getModuleName() . '/security/disable-notifications',
                        'token' => $token
                    ]);
                    ?>
                    <p style="margin: 0px; text-align: center">
                        <a href="<?= $disableNotificationLink; ?>"
                           title="<?= Yii::t('amosplatform', '#footer_disable_notification') ?>"
                           target="_blank"><?= Yii::t('amosplatform', '#footer_disable_notification') ?>
                        </a>
                    </p>
                <?php } ?>
                <?php if(!empty($profile)) {
                    $updateProfileLink = Yii::$app->urlManager->createAbsoluteUrl([
                        '/' . AmosAdmin::getModuleName() . '/user-profile/update',
                        'id' => $profile->id,
                        'tabActive' => 'tab-settings'
                    ]);
                    ?>
                    <p>
                        <?= Yii::t('amosplatform', 'Gestisci la frequenza delle email ricevute e la tua presenza nella piattaforma, ') ?>
                        <a href="<?= $updateProfileLink; ?>"
                           title="<?= Yii::t('amosplatform', '#login_profile') ?>"
                           target="_blank"><?= Yii::t('amosplatform', '#login_profile') ?>
                        </a>
                    </p>
                <?php } ?>
            </div>
        </td>
    </tr>
</table>
