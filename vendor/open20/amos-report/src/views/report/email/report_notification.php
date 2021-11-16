<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\report\views\report\email
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\report\AmosReport;

/**
 * @var \open20\amos\core\interfaces\ContentModelInterface $contentModel
 * @var \open20\amos\report\models\base\Report $report
 * @var \open20\amos\admin\models\UserProfile $reportCreatorProfile
 */

?>

<div>
    <div style="box-sizing:border-box;">
        <div style="padding:5px 10px;background-color: #F2F2F2;">
            <h1 style="color:#297A38;text-align:center;font-size:1.5em;margin:0;"><?= $reportCreatorProfile->getNomeCognome() . " " . AmosReport::t('amosreport', "#sent_a_report") ?></h1>
            <p><?= AmosReport::t('amosreport', "#for") . " " . $contentModel->getGrammar()->getArticleSingular() . " " . $contentModel->getGrammar()->getModelSingularLabel() ?>:</p>
        </div>
    </div>
    <div style="border:1px solid #cccccc;padding:10px;margin-bottom: 10px;background-color: #ffffff; margin-top: 20px;">
        <h2 style="font-size:2em;line-height: 1;">
            <?= $contentModel->getTitle() ?>
        </h2>
        <div>
            <div style="color:rgb(13,58,65);">
                <?= AmosReport::t('amosreport', "#with_text") ?>
                <p><?= Html::a($report->content, \Yii::$app->urlManager->createAbsoluteUrl($contentModel->getFullViewUrl()), ['style' => 'color: green;']) ?></p>
            </div>
        </div>
        <div style="margin-top:20px; /*border: 2px solid green; display: flex; padding: 10px;*/">
            <div style="width: 50px; height: 50px; overflow: hidden;-webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%;float: left;">
                <?= \open20\amos\admin\widgets\UserCardWidget::widget([
                    'model' => $reportCreatorProfile,
                    'onlyAvatar' => true,
                    'absoluteUrl' => true
                ]) ?>
            </div>
            <div style="margin: 15px 0 0 20px;">
                <?= AmosReport::t('amosreport', "#sent_by") ?> <span style="font-weight: 900;"><?= $reportCreatorProfile->getNomeCognome() ?></span>
            </div>
        </div>
        <div style="width:100%;margin-top:30px">
            <p><?= AmosReport::t('amosreport', 'You are receiving this notification because you are the creator/validator of the content') ?></p>
        </div>
    </div>
</div>
