<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\email
 * @category   CategoryName
 */
use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\record\Record;
use open20\amos\cwh\base\ModelContentInterface;
use open20\amos\core\forms\ItemAndCardHeaderWidget;

/**
 * @var Record|ContentModelInterface|ViewModelInterface $model
 * @var \open20\amos\admin\models\UserProfile $profile
 * @var Record[] $arrayModels
 */
if (!empty($profile)) {
    $this->params['profile'] = $profile;
}

$colors = \open20\amos\notificationmanager\utility\NotifyUtility::getColorNetwork($color);
?>

 
<?php
foreach ($arrayModels as $model) {
    $textButton      = \Yii::t('amosapp', 'Leggi');
    $commentsVisible = false;
    $commentsCount   = false;
    $textNewComments = '';

    if (method_exists($model, 'getNotifyTextButton')) {
        $textButton = $model->getNotifyTextButton();
    }
    if (!empty($arrayModelsComments)) {
        $count           = count($arrayModelsComments);
        $textNewComments = "<h2>$count ".($count == 1 ? \Yii::t('amosapp', 'Commento nuovo') : \Yii::t('amosapp',
                'Commenti nuovi'))."</h2>";
        $commentsCount   = true;
        if (!empty($arrayModelsComments[$model->id])) {
            $commentsVisible = true;
        }
    }
    ?>
    <tr>
        <td colspan="2" style="font-size:18px; font-weight:bold; padding: 5px 0 ; font-family: sans-serif;">
            <?=
            Html::a($model->getTitle(), Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()),
                ['style' => 'color: #000; text-decoration:none;'])
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-size:13px; color:#7d7d7d; padding:10px 0; font-family: sans-serif;"> <?= $model->getDescription(true); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding:15px 0 0 0;">
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
                        <table width="100%">
                            <tr>
                            <div style="box-sizing:border-box; /*padding: 10px 0; margin-top:10px;*/ float: left; width:100%;">
                                <?php
                                if ($commentsCount === true) {
                                    echo $textNewComments;
                                    if ($commentsVisible === true) {
                                        foreach ($arrayModelsComments[$model->id] as $comment) {
                                            echo $this->render('content_comment', ['comment' => $comment]);
                                        }
                                    }
                                }
                                ?>
                            </div>

                </tr>
            </table>
        </td>
        <td align="right" width="85" valign="bottom" style="text-align: center; padding-left: 10px;">
            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()) ?>" style="background: <?= $colors[1] ?>; border:3px solid <?= $colors[1] ?>; color: #ffffff; font-family: sans-serif; font-size: 11px; line-height: 22px; text-align: center; text-decoration: none; display: block; font-weight: bold; text-transform: uppercase; height: 20px;" class="button-a">
                <?php  if($commentsCount == false){  ?>
                <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]--><?= $textButton ?><!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                <?php  }  ?>
            </a>
        </td>
    </tr>

    </table>
    </td>
    </tr>
    <tr>
        <td colspan="2" style="border-bottom:1px solid #D8D8D8; padding:5px 0px"></td>
    </tr>


<?php } ?>


