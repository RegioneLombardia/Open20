<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    cruscottolavoro\activities\views\email
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\notificationmanager\widgets\ItemAndCardWidgetEmailSummaryWidget;
use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var \open20\amos\sondaggi\models\Sondaggi[] $arrayModels
 */

?>
<?php foreach ($arrayModels as $model): ?>
    <?php
    $modelTitle = $model->getTitle();
    $modelAbsoluteFullViewUrl = Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl());
    ?>
    <tr>
        <td colspan="2" style="padding-bottom:10px;">
            <table cellspacing="0" cellpadding="0" border="0" align="center" class="email-container" width="100%">
                <tr>
                    <td bgcolor="#FFFFFF" style="padding:15px;">
                        <table width="100%">
                            <tr>
                                <td style="font-size:18px; font-weight:bold; padding: 10px 0; font-family: sans-serif;">
                                    <?= Html::a($modelTitle, $modelAbsoluteFullViewUrl, [
                                        'style' => 'color: #000; text-decoration:none;',
                                        'title' => $modelTitle
                                    ]) ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size:11px; color:#4b4b4b; font-weight:bold;font-family: sans-serif;"><?= Yii::$app->formatter->asDate($model->created_at) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size:13px; color:#7d7d7d; padding:10px 0; font-family: sans-serif;"> <?= $model->getDescription(true) ?> </td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td width="400">
                                                <table width="100%">
                                                    <tr>
                                                        <?= ItemAndCardWidgetEmailSummaryWidget::widget(['model' => $model]); ?>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td align="right" width="85" valign="bottom" style="text-align: center; padding-left: 10px;">
                                                <a href="<?= $modelAbsoluteFullViewUrl ?>"
                                                   style="background: #297A38; border:3px solid #297A38; color: #ffffff; font-family: sans-serif; font-size: 11px; line-height: 22px; text-align: center; text-decoration: none; display: block; font-weight: bold; text-transform: uppercase; padding:1px;"
                                                   class="button-a">
                                                    <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                                                    <?= AmosSondaggi::t('amossondaggi', 'Partecipa'); ?><!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
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
<?php endforeach; ?>
