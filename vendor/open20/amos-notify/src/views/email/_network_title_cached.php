<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\email
 * @category   CategoryName
 */

use open20\amos\notificationmanager\AmosNotify;

/**
 * @var integer $contents_number
 */

$colors =  \open20\amos\notificationmanager\utility\NotifyUtility::getColorNetwork($color);
$urlIcon = \Yii::$app->params['platform']['frontendUrl'].\open20\amos\notificationmanager\utility\NotifyUtility::getIconNetwork($color);

?>

<tr>
    <td colspan="2" style="padding-top:15px;" width="100%">
        <table cellspacing="0" cellpadding="0" border="0" align="center" class="email-container" width="100%" style="width:100%">
            <tr>
                <td bgcolor="___network_color0___" align="center" style="width:40px; padding:5px"><img src="<?= $urlIcon ?>" height="20" border="0" align="center"></td>
                <td bgcolor="___network_color1___" style="font-family:sans-serif; color:#FFF; font-weight:bold; font-size:16px; padding:5px 10px; width:520px">
                    <p style="margin:8px 0;"><?= $modelNetwork->getTitle() ?></p>
                </td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td colspan="2" style="padding-bottom:10px;">
        <table cellspacing="0" cellpadding="0" border="0" align="center"   class="email-container" width="100%">
            <tr>
                <td bgcolor="#FFFFFF" style="padding:10px 15px 10px 15px; border-left:5px solid ___network_color2___">
                    <table width="100%">