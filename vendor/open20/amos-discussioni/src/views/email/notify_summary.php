
<?php
foreach ($arrayModels as $model) {
    $url = '/img/img_default.jpg';
    if (!is_null($model->discussionsTopicImage)) {
        $url = $model->discussionsTopicImage->getUrl('original', false, true);
    }
    ?>
    <tr>
        <td colspan="2" style="padding-bottom:10px;">
            <table cellspacing="0" cellpadding="0" border="0" align="center"   class="email-container" width="100%">
                <tr>
                    <td bgcolor="#FFFFFF" style="padding:10px 15px 10px 15px;">
                        <table width="100%">
                            <tr>
                                <td style="font-size:18px; font-weight:bold; padding: 5px 0 ; font-family: sans-serif;"><p style="margin:0">
                                        <?= \yii\helpers\Html::a($model->getTitle(),
                                            Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()),
                                            ['style' => 'color: #000; text-decoration:none;'])
                                        ?>
                                    </p>
                                    <p style="font-size:13px; color:#7d7d7d; padding:10px 0; font-family: sans-serif; font-weight:normal; margin:0"><?= $model->getDescription(true); ?></p>
                                </td>
                                <td width="100" valign="top" align="right"><img src="<?= $url ?>" width="85" height="85" border="0" align="center"></td>
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
                                            <td align="right" width="85" valign="bottom" style="text-align: center; padding-left: 10px;">
                                                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()) ?>" style="background: #297A38; border:3px solid #297A38; color: #ffffff; font-family: sans-serif; font-size: 11px; line-height: 22px; text-align: center; text-decoration: none; display: block; font-weight: bold; text-transform: uppercase; height: 20px;" class="button-a">
                                                    <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->Partecipa<!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                                                </a></td>
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
            </table>
        </td>
    </tr>
<?php } ?>