<?php

/**
 * Lombardia Informatica S.p.A.
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

$urlIcon = \Yii::$app->params['platform']['backendUrl']. $icon;
$colors =  \open20\amos\notificationmanager\utility\NotifyUtility::getColorNetwork($color);
?>

<tr>
    <td colspan="2" style="padding:10px 0">
        <table width="100%">
            <tr>
                <td valign="top" width="25">
                    <img src="<?= $urlIcon ?>"  height="20" border="0" align="center">
                </td>
                <td>
                    <strong style="font-family:sans-serif; font-size:16px; color:<?= $colors[1]?>; text-transform:uppercase;"><?= ucfirst($title) ?></strong>
                </td>
            </tr>
        </table>
    </td>
</tr>