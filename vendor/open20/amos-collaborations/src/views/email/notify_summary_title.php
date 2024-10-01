<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\email
 * @category   CategoryName
 */

use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\collaborations\utility\CollaborationProposalsEmailUtility;
use open20\amos\notificationmanager\AmosNotify;

/**
 * @var string $title
 */

$link = '';
$module = \Yii::$app->getModule('collaborations');
if (!is_null($module) && !empty($module->emailExternalUrl)) {
    $url = $module->emailExternalUrl;
    $link = CollaborationProposalsEmailUtility::getExternalLinkIcon($url);
}

$section_title = (new CollaborationProposals)->getGrammar()->getModelLabel();
$section_image = \Yii::$app->params['platform']['frontendUrl'] . '/img/icon_emails/icon-collaborazione-white.png';
$notifyModule = AmosNotify::instance();

?>

<!-- TITOLO SEZIONE : BEGIN -->
<tr>
    <td style="padding-top:15px;" width="100%">
        <table cellspacing="0" cellpadding="0" border="0" align="center" class="email-container" width="100%" style="width:100%">
            <?php if (!empty($section_title)) { ?>
                <tr>
                    <?php if (!empty($section_image)) { ?>
                        <td bgcolor="#204D28" align="center" style="width: 40px; padding: 5px; --darkreader-inline-bgcolor:#204426;" data-darkreader-inline-bgcolor="">
                            <img src="<?= $section_image ?>" height="20" border="0" align="center" class="CToWUd" data-bit="iit">
                        </td>
                    <?php } ?>
                    <td bgcolor="<?= $notifyModule->mailThemeColor['bgPrimary'] ?>" style="font-family:sans-serif; color:#FFF; font-weight:bold; font-size:18px; padding:5px 10px; text-transform: uppercase; width:520px">
                        <p style="margin:8px 0;">
                            <?= ucfirst($section_title) . $link ?>
                        </p>
                    </td>
                </tr>
            <?php } // $section_title ?>
            <?php if (!empty($section_description)) { ?>
                <tr>
                    <td style="font-family:sans-serif; font-size:14px; padding:5px 10px; width:520px">
                        <p style="margin:8px 0;">
                            <?= $section_description ?>
                        </p>
                    </td>
                </tr>
            <?php } // $section_description ?>
        </table>
    </td>
</tr>
<!-- TITOLO SEZIONE : END -->

