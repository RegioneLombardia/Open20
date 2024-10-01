<?php

/**
 * Aria S.p.A.
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
use open20\amos\notificationmanager\AmosNotify;

/**
 * @var Record|ContentModelInterface|ViewModelInterface $model
 * @var \open20\amos\admin\models\UserProfile $profile
 * @var Record[] $arrayModels
 */
if (!empty($profile)) {
    $this->params['profile'] = $profile;
}

$colors = \open20\amos\notificationmanager\utility\NotifyUtility::getColorNetwork($color);
$notifyModule = AmosNotify::instance();

?>

 
<?php
foreach ($arrayModels as $model) {
    $textButton      = Yii::t('amosapp', 'Leggi');
    $commentsVisible = false;
    $commentsCount   = false;
    $textNewComments = '';

    if (method_exists($model, 'getNotifyTextButton')) {
        $textButton = $model->getNotifyTextButton();
    }
    if (!empty($arrayModelsComments)) {
        $count           = count($arrayModelsComments);
        $textNewComments = "<h2>$count ".($count == 1 ? Yii::t('amosapp', 'Commento nuovo') : Yii::t('amosapp',
                'Commenti nuovi'))."</h2>";
        $commentsCount   = true;
        if (!empty($arrayModelsComments[$model->id])) {
            $commentsVisible = true;
        }
    }
    ?>
    <!-- Hero Image, Flush : BEGIN -->
    <tr>
        <td>
            <?php
                $url = '/img/img_default.jpg';
                $image=$model->getModelImage();
                if (!is_null($image)) {
                    $url = $image->getWebUrl('square_large', false, true);
                }
                $url =  Yii::$app->urlManager->createAbsoluteUrl($url);
            ?>
            <img src="<?= $url ?>" border="0" width="570" align="center" style="max-width: 100%; width:100%;">
        </td>
    </tr>
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
                                <td style="font-size:11px; color:#4b4b4b; font-weight:bold;font-family: sans-serif;"><?= Yii::$app->formatter->asDate($model->created_at, 'yyyy-MM-dd') ?></td>
                            </tr>
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
        <?php  if($commentsCount === false){  ?>
            <td align="right" width="85" valign="bottom" style="text-align: center; padding-left: 10px;">
                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()) ?>"
                
                style="background: <?= $notifyModule->mailThemeColor['bgPrimary'] ?>; border:3px solid <?= $notifyModule->mailThemeColor['bgPrimary'] ?>; color: <?= $notifyModule->mailThemeColor['textContrastBgPrimary'] ?>; font-family: sans-serif; font-size: 11px; line-height: 0px; text-align: center; text-decoration: none; display: block; font-weight: bold; text-transform: uppercase; height: 24px; padding:12px 0" class="button-a">
                    
                    <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]--><?= $textButton ?><!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                    
                </a>
            </td>
        <?php  }  ?>
    </tr>

    </table>
    </td>
    </tr>
    <tr>
        <td colspan="2" style="border-bottom:1px solid #D8D8D8; padding:5px 0px"></td>
    </tr>


<?php } 


