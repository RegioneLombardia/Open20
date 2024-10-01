<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\record\Record;
use open20\amos\notificationmanager\AmosNotify;

$textButton = Yii::t('amosapp', 'Leggi');
$commentsVisible = false;
$commentsCount = false;
$textNewComments = '';
$classname = get_class($model);


if (method_exists($model, 'getNotifyTextButton')) {
    $textButtonTmp = $model->getNotifyTextButton();
    if (!empty($textButtonTmp)) {
        $textButton = $textButtonTmp;
    }
}

if (!empty($renderedComments[$classname][$model->id])) {
    $count = count($renderedComments[$classname][$model->id]);

    $textNewComments = "<span style='display:inline-block;margin:2px;text-transform:uppercase;color:#003354;border:1px solid #003354;height:16px;line-height:16px;font-size:10px;vertical-align:top;padding:0 5px'>$count " . ($count == 1 ? Yii::t('amosapp', 'Commento nuovo') : Yii::t(
        'amosapp',
        'Commenti nuovi'
    )) . "</span>";
    $commentsCount = $count > 0;
    if (!empty($renderedComments[$classname][$model->id]) && $count == 1) {
        $commentsVisible = true;
    }
}
?>


<tr>
    <td colspan="2" style="font-size:18px; font-weight:bold; padding: 5px 0 ; font-family: sans-serif;">
        <?=
        Html::a(
            $model->getTitle(),
            Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()),
            ['style' => 'color: #000; text-decoration:none;']
        )
        ?>
        <?php
        if ($commentsCount === true) {
            echo $textNewComments;
            if ($commentsVisible === true) {
                $comments = $renderedComments[$classname][$model->id];
                foreach ($comments as $commentHtml) {
                    echo $commentHtml;
                }
            }
        }
        ?>
    </td>
</tr>
<?php if ($commentsVisible == false) { ?>
<tr>
    <td colspan="2" style="font-size:13px; color:#7d7d7d; padding:0  0 10px 0; font-family: sans-serif;"> <?= $model->getDescription(true); ?></td>
</tr>
<tr>
    <td colspan="2" style="padding:0;">
        <table width="100%">
            <tr>
                <td width="400">
                    <table width="100%">
                        <tr>
                            <?=
                            \open20\amos\notificationmanager\widgets\ItemAndCardWidgetEmailSummaryWidget::widget([
                                'model' => $model,
                            ]);
                            ?>

                        </tr>
                    </table>

                </td>

                <?php if ($commentsVisible == false) { ?>
                    <td align="right" width="85" valign="bottom" style="text-align: center; padding-left: 10px;">
                        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()) ?>" style="background: ___network_color1___; border:3px solid ___network_color1___; color: #ffffff; font-family: sans-serif; font-size: 11px; line-height: 22px; text-align: center; text-decoration: none; display: block; font-weight: bold; text-transform: uppercase; height: 20px;" class="button-a">
                            <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                            <?= $textButton ?>
                            <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                        </a>
                    </td>
                <?php } ?>

            </tr>

        </table>
    </td>
</tr>
<?php } ?>
<tr>
    <td colspan="2" style="border-bottom:1px solid #D8D8D8; padding:5px 0px; margin-bottom:10px; display:block;"></td>
</tr>