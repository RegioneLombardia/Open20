
<?php foreach ($arrayModels as $model) { ?>
<tr>
    <td colspan="2" style="padding-bottom:10px;">
        <table cellspacing="0" cellpadding="0" border="0" align="center"   class="email-container" width="100%">
            <tr>
                <td bgcolor="#FFFFFF" style="padding:15px;">
                    <table width="100%">
                        <!-- Hero Image, Flush : BEGIN -->
                        <tr>
                            <td>
                                <?php
                                $url = '/img/img_default.jpg';
                                if (!is_null($model->newsImage)) {
                                    $url = $model->newsImage->getUrl('square_large', false, true);
                                }
                                $url =  Yii::$app->urlManager->createAbsoluteUrl($url);
                                ?>
                                <img src="<?= $url ?>" border="0" width="570" align="center" style="max-width: 570px; width:100%;">
                            </td>
                        </tr>
                        <!-- Hero Image, Flush : END -->
                        <tr>
                            <td style="font-size:18px; font-weight:bold; padding: 10px 0; font-family: sans-serif;">
                                <?= \yii\helpers\Html::a($model->getTitle(), Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()), ['style' => 'color: #000; text-decoration:none;']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:11px; color:#4b4b4b; font-weight:bold;font-family: sans-serif;"><?= $model->getPublicatedFrom()?></td>
                        </tr>
                        <tr>
                            <td style="font-size:13px; color:#7d7d7d; padding:10px 0; font-family: sans-serif;"> <?= $model->descrizione_breve ?> </td>
                        </tr>
                        <tr>
                            <td>
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
                                        <td align="right" width="85" valign="bottom" style="text-align: center; padding-left: 10px;">
                                            <a href="<?=  Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl())?>" style="background: #297A38; border:3px solid #297A38; color: #ffffff; font-family: sans-serif; font-size: 11px; line-height: 22px; text-align: center; text-decoration: none; display: block; font-weight: bold; text-transform: uppercase; height: 20px;" class="button-a">
                                                <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->Leggi<!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
    </td>
</tr>
<?php } ?>