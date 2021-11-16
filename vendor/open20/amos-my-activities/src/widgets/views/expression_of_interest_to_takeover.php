<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\widgets\views
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\myactivities\AmosMyActivities;

/**
 * @var yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $userProfile
 * @var \open20\amos\een\models\EenExprOfInterest $model
 * @var string $validationRequestTime
 * @var string $labelKey
 */

?>

<div class="col-md-3 col-xs-5 wrap-user">
    <?= UserCardWidget::widget(['model' => $userProfile]) ?>
    <span class="user"><?= $userProfile->getNomeCognome() ?></span>
    <br>
    <?= AmosAdmin::t('amosadmin', $userProfile->workflowStatus->label) ?>
</div>
<div class="col-md-5 col-xs-5 wrap-report">
    <div class="col-lg-12 col-xs-12">
        <strong><?= $labelKey ?></strong>
    </div>
    <div class="col-lg-12 col-xs-12">
        <?= Yii::$app->formatter->asDatetime($validationRequestTime) ?>
    </div>
    <div class="col-lg-12 col-xs-12">
        <p class="user-report"><?= $userProfile->getNomeCognome() ?></p>
        <?= AmosMyActivities::t('amosmyactivities', ' asks validation for:'); ?>
        <?php /** @var \open20\amos\core\interfaces\ContentModelInterface $model */ ?>
        <?= $model->getTitle() ?>
    </div>
    <div class="col-lg-12 col-xs-12">
        <?php /** @var \open20\amos\core\interfaces\ViewModelInterface $model */ ?>
        <?= Html::a(AmosIcons::show('search', [], 'dash') . ' <span>' . AmosMyActivities::t('amosmyactivities',
                'View card') . '</span>', ['/een/een-partnership-proposal/view', 'id' => $model->een_partnership_proposal_id]
//            Yii::$app->urlManager->createUrl([
//                '/community/community/view',
//                'id' => $model->id
//            ])
        ) ?>
    </div>
</div>
