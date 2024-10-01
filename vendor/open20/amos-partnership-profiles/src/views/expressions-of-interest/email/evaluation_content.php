<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\expressions-of-interest\email
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;
use open20\amos\partnershipprofiles\Module;

/**
 * @var ExpressionsOfInterest $expressionOfInterest
 * @var string $relevantStr
 */

?>
<div style="border:1px solid #cccccc;padding:10px;margin-bottom: 10px;background-color: #ffffff;">

    <div>
        <div style="margin-top: 20px;color:#000000;margin-left: 10px;">
            <h2 style="font-size:1.5em;line-height: 1;"><?= $expressionOfInterest->partnershipProfile->createdUserProfile->getNomeCognome() . ' ' . $relevantStr . ' ' . Module::t('amospartnershipprofiles', 'the expression of interest you sent for the partnership profile') ?></h2>
        </div>
    </div>

    <div style="padding:0;margin:0">
        <h3 style="font-size:2em;line-height: 1;margin:0;padding:10px 0;">
            <?= Html::a($expressionOfInterest->partnershipProfile->getTitle(), Yii::$app->urlManager->createAbsoluteUrl($expressionOfInterest->partnershipProfile->getFullViewUrl()), ['style' => 'color: #297A38;']) ?>
        </h3>
    </div>

    <div style="box-sizing:border-box;font-size:13px;font-weight:normal;color:#000000;">
        <?= $expressionOfInterest->partnershipProfile->getDescription(true); ?>
    </div>
</div>