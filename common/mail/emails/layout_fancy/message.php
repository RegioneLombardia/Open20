<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */


use open20\amos\admin\models\UserProfile;
$moduleNameAdmin = \open20\amos\admin\AmosAdmin::getModuleName();

$appLink = Yii::$app->urlManager->createAbsoluteUrl(['/']);
$appName = Yii::$app->name;
?>
<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td style="margin-bottom:10px;background-color:#297A38;height:15px"></td>
        <td style="margin-bottom:10px;background-color:#297A38;height:15px"></td>
        <td style="margin-bottom:10px;background-color:#297A38;height:15px"></td>
    </tr>
    <tr>
        <td style="height:10px"></td>
    </tr>
    <tr style="background-color:#ffffff;">
        <td>
            <?php if (isset(Yii::$app->params['logoMail'])) {
                $logoMail = $appLink . Yii::$app->params['logoMail'];
            } else {
                $logoMail = '';
            } ?>
            <img style="max-width:500px; max-height:60px;" src="<?= $logoMail ?>" alt="logo">
        </td>
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
            <div style="font-style: italic; color: #b0b0b0; margin-top:10px;border-top: 2px solid #297a38;padding-top: 5px;font-size: 11px;line-height: normal;">
                <?= Yii::t('amosplatform', '#footer_template_mail', [
                    'appName' => $appName,
                ]) ?>
                <p style="margin: 0px;">
                    <a href="<?= $appLink ?>site/privacy"
                      title="<?= Yii::t('amosplatform', '#footer_template_mail_privacy_title') ?>"
                      target="_blank"><?= Yii::t('amosplatform', '#footer_template_mail_privacy') ?>
                    </a>
                    <br>
                    <?php if(!empty(\Yii::$app->params['urlPersonalizedPrivacy'])) {?>
                        <a href="<?= $appLink ?>"<?= \Yii::$app->params['urlPersonalizedPrivacy'] ?>
                           title="<?= Yii::t('amosplatform', '#information_personalized') ?>"
                           target="_blank"><?= Yii::t('amosplatform', '#information_personalized') ?>
                        </a>
                    <?php }  ?>
                </p>
                <br>
                <?php if(!empty($this->params['profile'])) {
                    /** @var UserProfile $profile */
                    $profile = $this->params['profile'];
                    $token = md5($profile->user_id . $appName . $profile->user->username);
                }?>

                <?php if(!empty($token)) {?>
                    <p style="margin: 0px; text-align: center">
                        <a href="<?= $appLink ?><?= $moduleNameAdmin ?>/security/disable-notifications?token=<?=$token?>"
                           title="<?= Yii::t('amosplatform', '#footer_disable_notification') ?>"
                           target="_blank"><?= Yii::t('amosplatform', '#footer_disable_notification') ?>
                        </a>
                    </p>
                <?php } ?>
                <?php if(!empty($profile)) {?>
                    <p>
                        <?= Yii::t('amosplatform', 'Gestisci la frequenza delle email ricevute e la tua presenza nella piattaforma, ') ?>
                        <a href="<?= $appLink ?><?= $moduleNameAdmin ?>/user-profile/update?id=<?= $profile->id?>&tabActive=tab-settings"
                           title="<?= Yii::t('amosplatform', '#login_profile') ?>"
                           target="_blank"><?= Yii::t('amosplatform', '#login_profile') ?>
                        </a>
                    </p>
                <?php } ?>
            </div>
        </td>
    </tr>
</table>
