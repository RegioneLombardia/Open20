<?php
use open20\amos\notificationmanager\AmosNotify;

/**
 * @var $comment
 */

$notifyModule = AmosNotify::instance();
$contextClassname = $comment->context;
$modelContext = $contextClassname::findOne($comment->context_id);
$viewUrl = '';
if($modelContext) {
    $viewUrl =  Yii::$app->urlManager->createAbsoluteUrl($modelContext->getFullViewUrl());
}
?>

<tr>
    <td style="padding:0">
        <table width="100%">
            <tbody><tr>
                <td width="400" style="text-align:left">
                    <table width="100%">
                        <tbody><tr>
                            <td colspan="2" style="text-align:left">
                                <strong style="font-family:sans-serif;font-size:11px;color:#000"><?= $comment->createdUserProfile->nomeCognome?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:left">
                                <em style="font-family:sans-serif;font-size:14px;color:#000">"<?= Yii::$app->getFormatter()->asRaw(strip_tags($comment->comment_text)) ?>"</em>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td align="right" width="85" height="20" valign="bottom" style="text-align:center;padding-left:10px">
                    <a style="background: ___network_color1___; border:3px solid ___network_color1___; color: #ffffff; font-family: sans-serif; font-size: 11px; line-height: 22px; text-align: center; text-decoration: none; display: block; font-weight: bold; text-transform: uppercase; height: 20px;"
                       class="button-a" href="<?= $viewUrl ?>">
                        <?= AmosNotify::t('amosnotify', "Rispondi")?>
                    </a>
                </td>
            </tr>

            </tbody>
        </table>
    </td>
</tr>