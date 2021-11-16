<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

$moduleNameAdmin = \open20\amos\admin\AmosAdmin::getModuleName();
$appLink = Yii::$app->urlManager->createAbsoluteUrl(['/']);
$appName = Yii::$app->name;

?>

<table bgcolor="#F6F6F6" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;">
    <tr>
        <td bgcolor="#ffffff" style="padding:0 15px; border-top:15px solid #297A38">
            <center style="width: 100%;">
                <table cellspacing="0" cellpadding="0" border="0" align="center" width="590" class="email-container">
                    <tr>
                        <td style="padding:15px 0">
                          <?php if (isset(Yii::$app->params['logoMail'])) {
                              $logoMail = $appLink . Yii::$app->params['logoMail'];
                          } else {
                              $logoMail = '';
                          } ?>
                            <img width="420" src="<?= $logoMail ?>" style="width:420px;" border="0">
                        </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
</table>
<?php //if ($heading) { ?>
<!--    <table width=" 600" border="0" cellpadding="0" cellspacing="0" align="center">-->
<!--        <tr>-->
<!--            <td align="center" valign="top">-->
<!--                <h1 style="padding-top: 25px; color:green;margin:0;display:block;font-family:Arial;font-size:25px;font-weight:bold;text-align:center;line-height:150%">--><?php //echo $heading; ?><!--</h1>-->
<!--            </td>-->
<!--        </tr>-->
<!--    </table>-->
<?php //} ?>
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
        <td bgcolor="#ffffff" style="padding:0 15px;">
            <center style="width: 100%;">
            <table cellspacing="0" cellpadding="0" border="0" align="center" width="590" class="email-container">
                <tr>
                <td style="padding:15px 0; text-align:center; font-family:sans-serif; font-size:11px; color:#9C9C9C;">

                <?= Yii::t('amosapp', '#footer_template_mail', [
                    'appName' => $appName,
                ]) ?>
                <p style="margin: 0px;">
                    <a href="<?= $appLink ?>site/privacy"
                       title="<?= Yii::t('amosapp', '#footer_template_mail_privacy_title') ?>"
                       target="_blank"><?= Yii::t('amosapp', '#footer_template_mail_privacy') ?>
                    </a>
                    <br>
                    <?php if(!empty(\Yii::$app->params['urlPersonalizedPrivacy'])) {
                        ?>
                        <a href="<?= $appLink ?>"<?= \Yii::$app->params['urlPersonalizedPrivacy'] ?>
                           title="<?= Yii::t('poiplatform', '#privacy_regione_lombardia') ?>"
                           target="_blank"><?= Yii::t('poiplatform', '#privacy_regione_lombardia') ?>
                        </a>
                    <?php }  ?>
                </p>
                <br>
                <?php if(!empty($this->params['profile'])) {
                    $profile = $this->params['profile'];
                    $token = md5($profile->user_id.$appName.$profile->user->username);
                }?>

                <?php if(!empty($token)) {?>
                    <p style="margin: 0px; text-align: center">
                        <a href="<?= $appLink ?><?= $moduleNameAdmin ?>/security/disable-notifications?token=<?=$token?>"
                           title="<?= Yii::t('amosapp', '#footer_disable_notification') ?>"
                           target="_blank"><?= Yii::t('amosapp', '#footer_disable_notification') ?>
                        </a>
                    </p>
                <?php } ?>
                <?php if(!empty($profile)) {?>
                    <p>
                        <?= Yii::t('amosapp', 'Gestisci la frequenza delle email ricevute e la tua presenza nella piattaforma, ') ?>
                        <a href="<?= $appLink ?><?= $moduleNameAdmin ?>/user-profile/update?id=<?= $profile->id?>&tabActive=tab-settings"
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
