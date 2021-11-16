<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\email
 * @category   CategoryName
 */

/**
 * @var string $title
 */

$urlIcon = \Yii::$app->params['platform']['backendUrl']. $icon;

?>

<!-- TITOLO VERDE : BEGIN -->
<tr>
    <td style="padding-top:15px;" width="100%">
    <table cellspacing="0" cellpadding="0" border="0" align="center" class="email-container" width="100%" style="width:100%">
        <tr>
            <td bgcolor="#204D28" align="center" style="width:40px; padding:5px"><img src="<?= $urlIcon ?>" height="20" border="0" align="center"></td>
            <td bgcolor="#297A38" style="font-family:sans-serif; color:#FFF; font-weight:bold; font-size:18px; padding:5px 10px; text-transform: uppercase; width:520px"><p style="margin:8px 0;"><?= ucfirst($title) ?></p></td>
        </tr>
    </table>               
    </td>  
</tr>
<!-- TITOLO VERDE : END -->