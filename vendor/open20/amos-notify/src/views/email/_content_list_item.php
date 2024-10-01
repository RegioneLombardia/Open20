<?php
/**
 * @var $model
 */
use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ModelImageInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\record\Record;
use open20\amos\notificationmanager\AmosNotify;

$modelTitle = $model->getTitle();
$modelAbsoluteFullViewUrl = Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl());
?>
<tr>
    <td bgcolor="#FFFFFF" style="padding:10px 15px 10px 15px;">
        <table width="100%">
            <?php if ($model instanceof ModelImageInterface): ?>
                <!-- Hero Image, Flush : BEGIN -->
                <tr>
                    <td>
                        <?php
                        $url = $model->getModelImageUrl('square_large', false, '/img/img_default.jpg', false, true);
                        if ($model instanceof ContentModelInterface) {
                            $imageAlt = $model->getTitle();
                        } else {
                            $imageAlt = AmosNotify::t('amosnotify', '#content_image');
                        }
                        ?>
                        <img src="<?= Yii::$app->urlManager->createAbsoluteUrl($url); ?>" width="570" align="center" style="max-width: 100%; width:100%; border: 0;" alt="<?= $imageAlt; ?>">
                    </td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2" style="font-size:18px; font-weight:bold; padding: 5px 0 ; font-family: sans-serif;">
                    <?= Html::a($modelTitle, $modelAbsoluteFullViewUrl, [
                        'style' => 'color: #000; text-decoration:none;',
                        'title' => $modelTitle
                    ]) ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-size:13px; color:#7d7d7d; padding:10px 0; font-family: sans-serif;"><?= $model->getDescription(true); ?></td>
            </tr>
            <tr>
                <td colspan="2" style="padding:15px 0 0 0;">
                    <table width="100%">
                        <tr>
                            <td width="400">
                                <table width="100%">
                                    <tr>
                                        <?= \open20\amos\notificationmanager\widgets\ItemAndCardWidgetEmailSummaryWidget::widget([
                                            'model' => $model,
                                        ]); ?>
                                    </tr>
                                </table>
                            </td>
                            <td align="right" width="85" valign="bottom" style="text-align: center; padding-left: 10px;"><a href="<?= $modelAbsoluteFullViewUrl ?>"
                                                                                                                            style="background: <?= $notifyModule->mailThemeColor['bgPrimary'] ?>; border:3px solid <?= $notifyModule->mailThemeColor['bgPrimary'] ?>; color: <?= $notifyModule->mailThemeColor['textContrastBgPrimary'] ?>; font-family: sans-serif; font-size: 11px; line-height: 0px; text-align: center; text-decoration: none; display: block; font-weight: bold; text-transform: uppercase; height: 20px; padding:12px 0" class="button-a">
                                    <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]--><?= AmosNotify::t('amosnotify', '#read'); ?><!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border-bottom:1px solid #D8D8D8; padding:5px 0px"></td>
            </tr>
        </table>
    </td>
</tr>