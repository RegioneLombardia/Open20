<?php

use \open20\amos\notificationmanager\AmosNotify;

$colors = \open20\amos\notificationmanager\utility\NotifyUtility::getColorNetwork($color);
?>
<?php
foreach ($arrayModels as $model) {
    $textCallToAction = 'Partecipa';
    $url              = '/img/img_default.jpg';
    if (!is_null($model->discussionsTopicImage)) {
        $url = $model->discussionsTopicImage->getUrl('original', false, true);
    }
//
    $count = 0;
    if (!empty($arrayModelsComments)) {
//                $count = count($arrayModelsComments);
//                echo "<h2>$count Commenti nuovi</h2>";
        if (!empty($arrayModelsComments[$model->id])) {
            $count = count($arrayModelsComments[$model->id]);
//                    foreach ($arrayModelsComments[$model->id] as $comment) {
//                        echo $this->render('content_comment', ['comment' => $comment]);
//                    }
        }
    }
    if ($count == 1) {
        $nuovoText = AmosNotify::t('amosnotify', 'nuovo commento');
    } else {
        $nuovoText = AmosNotify::t('amosnotify', 'nuovi commenti');
    }
    ?>


    <tr>
        <td colspan="2" style="padding-bottom:10px;">
            <table width="100%">
                <tr>
                    <td valigh="top" style="font-size:16px; font-weight:bold; padding: 5px 15px 5px 0px; font-family: sans-serif; text-align:left; vertical-align:top;"><p style="margin:0 0 5px 0">
                            <?=
                            \yii\helpers\Html::a($model->getTitle(),
                                Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()),
                                ['style' => 'color: #000; text-decoration:none;'])
                            ?>
                            <?php if ($count > 0) { ?>
                                <span style="display: inline-block; margin: 2px;text-transform: uppercase; color: <?= $colors[1] ?>; border: 1px solid <?= $colors[1] ?>; height: 16px; line-height: 16px; font-size: 10px; vertical-align:top; padding: 0 5px;" class="tag">
                                    <?= $count ?> <?= $nuovoText ?>
                                </span></p>
                        <?php } else { ?>

                            <span style="display: inline-block; margin: 2px;text-transform: uppercase; color: <?= $colors[1] ?>; border: 1px solid <?= $colors[1] ?>; height: 16px; line-height: 16px; font-size: 10px; vertical-align:top; padding: 0 5px;" class="tag">
                                <?= AmosNotify::t('amosnotify', 'nuova discussione') ?>
                            </span></p>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 0 10px 0; border-bottom:1px solid #D8D8D8;">
                        <table width="100%">
                            <tr>
                                <td width="400" style="text-align:left;">
                                    <table width="100%">
                                        <?php
                                        if ($count == 1) {
                                            $textCallToAction = 'Rispondi';
                                            $comment          = $arrayModelsComments[$model->id][0];
                                            $textComment      = '';
                                            if (!empty($comment)) {
                                                $textComment = strip_tags($comment->comment_text);
                                                ?>

                                                <tr>
                                                    <td colspan="2" style="text-align:left;">
                                                        <strong style="font-family: sans-serif; font-size:11px; color:#000"><?= $comment->createdUserProfile ?></strong>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2" style="text-align:left;">
                                                    <em style="font-family: sans-serif; font-size:14px; color:#000">"<?= $textComment ?>"</em>
                                                </td>
                                            </tr>
                                            <?php
                                        } else if ($count > 1) {
                                            $textCallToAction = 'Leggi';
                                            ?>
                                        <?php } else { ?>
                                            <tr>
                                                <?=
                                                \open20\amos\notificationmanager\widgets\ItemAndCardWidgetEmailSummaryWidget::widget([
                                                    'model' => $model,
                                                ]);
                                                ?>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </td>
                                <td align="right" width="85" height="20" valign="bottom" style="text-align: center; padding-left: 10px;"><a href="<?= Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()) ?>" style="background: <?= $colors[1] ?>; border:3px solid <?= $colors[1] ?>; color: #ffffff; font-family: sans-serif; font-size: 11px; line-height: 22px; text-align: center; text-decoration: none; display: block; font-weight: bold; text-transform: uppercase; height: 20px;" class="button-a">
                                        <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]--><?=
                                        \Yii::t('amosapp', $textCallToAction)
                                        ?><!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                                    </a></td>
                            </tr>

                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
    <!--                --><?php
//            if(!empty($arrayModelsComments)) {
//                $count = count($arrayModelsComments);
//                echo "<h2>$count Commenti nuovi</h2>";
//                if(!empty($arrayModelsComments[$model->id])) {
//                    foreach ($arrayModelsComments[$model->id] as $comment) {
//                        echo $this->render('content_comment', ['comment' => $comment]);
//                    }
//                }
//            }
    ?>
<?php } ?>