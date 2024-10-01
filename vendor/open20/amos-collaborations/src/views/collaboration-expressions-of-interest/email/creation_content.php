<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\views\collaboration-expressions-of-interest\email
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\collaborations\models\CollaborationExpressionsOfInterest;
use open20\amos\collaborations\Module;

/**
 * @var ExpressionsOfInterest $expressionOfInterest
 */

?>
<div style="border:1px solid #cccccc;padding:10px;margin-bottom: 10px;background-color: #ffffff;">

    <div>
        <div style="margin-top: 20px;color:#000000;margin-left: 10px;margin-right: 10px">
            <h2 style="font-size:13px;line-height: 1;"><?= \Yii::t('amoscollaborations', 'L\'utente') . ' ' . $expressionOfInterest->createdUserProfile->getNomeCognome() . ' ' . \Yii::t('amoscollaborations', 'ha manifestato il proprio interesse verso la proposta') . ': ' ?></h2>
        </div>
    </div>

    <div style="padding:0;margin:0">
        <h3 style="font-size:2em;line-height: 1;margin-left:10px;margin-right: 10px;padding:10px 0;">
            <?= Html::a($expressionOfInterest->collaborationProposals->getTitle(), Yii::$app->urlManager->createAbsoluteUrl($expressionOfInterest->collaborationProposals->getFullViewUrl()), ['style' => 'color: #297A38;']) ?>
        </h3>
    </div>
    <?php if ($expressionOfInterest->manifestazioneinteresse) { ?>
        <div style="box-sizing:border-box;font-size:13px;font-weight:normal;margin-left:10px;margin-right: 10px;color:#000000;">
            <?= \Yii::t('amoscollaborations', 'Indicando la seguente motivazione: ') . $expressionOfInterest->manifestazioneinteresse ?>
        </div>
    <?php } ?>
    <br>
    <div style="box-sizing:border-box;font-size:13px;font-weight:normal;margin-left:10px;margin-right: 10px;color:#000000;">
        <?= \Yii::t('amoscollaborations', 'L\'utente che ha posto la richiesta di collaborazione ha indicato i seguenti dati di contatto:') ?>
    </div>
    <br>
    <div style="box-sizing:border-box;font-size:13px;font-weight:normal;margin-left:10px;margin-right: 10px;color:#000000;">
        <strong><?= \Yii::t('amoscollaborations', 'Stato di provenienza') ?></strong><?= ': ' . $expressionOfInterest->getCountryName($expressionOfInterest->country) ?>
    </div>

    <?php if ($expressionOfInterest->city) { ?>
        <div style="box-sizing:border-box;font-size:13px;font-weight:normal;margin-left:10px;margin-right: 10px;color:#000000;">
            <strong><?= \Yii::t('amoscollaborations', 'Città di provenienza') ?></strong><?= ': ' . $expressionOfInterest->getCityName($expressionOfInterest->city) ?>
        </div>
    <?php } ?>

    <?php if ($expressionOfInterest->province) { ?>
        <div style="box-sizing:border-box;font-size:13px;font-weight:normal;margin-left:10px;margin-right: 10px;color:#000000;">
            <strong><?= \Yii::t('amoscollaborations', 'Provincia di provenienza') ?></strong><?= ': ' . $expressionOfInterest->getProvinceName($expressionOfInterest->province) ?>
        </div>
    <?php } ?>

    <?php if ($expressionOfInterest->municipality) { ?>
        <div style="box-sizing:border-box;font-size:13px;font-weight:normal;margin-left:10px;margin-right: 10px;color:#000000;">
            <strong><?= \Yii::t('amoscollaborations', 'Comune di provenienza') ?></strong><?= ': ' . $expressionOfInterest->municipality ?>
        </div>
    <?php } ?>

    <?php if ($expressionOfInterest->address) { ?>
        <div style="box-sizing:border-box;font-size:13px;font-weight:normal;margin-left:10px;margin-right: 10px;color:#000000;">
            <strong><?= \Yii::t('amoscollaborations', 'Indirizzo di provenienza') ?></strong><?= ': ' . $expressionOfInterest->address ?>
        </div>
    <?php } ?>

    <?php if ($expressionOfInterest->postal_code) { ?>
        <div style="box-sizing:border-box;font-size:13px;font-weight:normal;margin-left:10px;margin-right: 10px;color:#000000;">
            <strong><?= \Yii::t('amoscollaborations', 'CAP') ?></strong><?= ': ' . $expressionOfInterest->postal_code ?>
        </div>
    <?php } ?>

    <div style="box-sizing:border-box;font-size:13px;font-weight:normal;margin-left:10px;margin-right: 10px;color:#000000;">
        <strong><?= \Yii::t('amoscollaborations', 'Telefono') ?></strong><?= ': ' . $expressionOfInterest->proposer_phone ?>
    </div>
</div>