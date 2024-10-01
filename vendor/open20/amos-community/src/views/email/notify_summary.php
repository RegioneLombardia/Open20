<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\record\Record;
use open20\amos\cwh\base\ModelContentInterface;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\community\i18n\grammar\BookmarksGrammar;
use open20\amos\core\module\BaseAmosModule;
use Yii;

?>

<tr>
    <td colspan="2">
        <table cellspacing="0" cellpadding="0" border="0" align="center" class="email-container" width="100%">
            <?php foreach ($arrayModels as $model){ ?>
                <tr>
                    <td bgcolor="#FFFFFF" style="padding:10px 15px 10px 15px;">
                        <table width="100%">
                            <tr>
                                <td colspan="2" style="font-size:15px;padding:5px 0;font-family:sans-serif;line-height: 1">
                                    <p><?= BaseAmosModule::t('community', 'Un amministratore ha accettato la richiesta di pubblicazione del bookmark:') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-size:18px; font-weight:bold; padding: 5px 0 ; font-family: sans-serif;">
                                    <?= Html::a($model->getTitle(), $model->link, ['style' => 'color: #000; text-decoration:none;']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-size:13px; padding: 10px; 0; color:#7d7d7d; font-family: sans-serif;">
                                    <?= Yii::$app->formatter->asDate($model->data_pubblicazione, 'yyyy-MM-dd') ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
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
                                                <a href="<?= $model->link ?>" style="background: #297A38; border:3px solid #297A38; color: #ffffff; font-family: sans-serif; font-size: 11px; line-height: 22px; text-align: center; text-decoration: none; display: block; font-weight: bold; text-transform: uppercase; padding:1px;" class="button-a">
                                                    <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->Vai al link<!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
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
            <?php } ?>
        </table>
    </td>
</tr>
