<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\core\icons\AmosIcons;

/** @var $model \open20\amos\community\models\Community */
/** @var $published bool - If the community is in published status */
/** @var $message string */

$this->title = $model;

?>
<div class="closing progress-container">

    <h2 class="part"><?= AmosCommunity::tHtml('amoscommunity', 'Closing') ?></h2>
    <div class="row m-b-30">
        <div class="col-xs-12">
            <div class="pull-left">
                <div class="like-widget-img color-primary m-t-15">
                    <?= AmosIcons::show('group', [], 'dash'); ?>
                </div>
            </div>
            <div class="text-wrapper">
                <?php if ($published): ?>
                    <h3><?= AmosCommunity::tHtml('amoscommunity', "#community_success") ?></h3>
                <?php else: ?>
                    <h3><?= $message ?></h3>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?php if ($published): ?>
    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'continueLabel' => AmosCommunity::tHtml('amoscommunity', "#manage_invite_participants"),
        'finishUrl' => Yii::$app->urlManager->createUrl(['community/community/update', 'id' => $model->id, 'tabActive' => 'tab-participants']),
        'previousUrl' => Yii::$app->session->get(AmosCommunity::beginCreateNewSessionKey()),
        'previousLabel' => AmosCommunity::tHtml('amoscommunity', "#back_to_communities")
    ]) ?>
<?php else: ?>
    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'viewPreviousBtn' => false,
        'continueLabel' => AmosCommunity::tHtml('amoscommunity', '#back_to_communities'),
        'finishUrl' => Yii::$app->session->get(AmosCommunity::beginCreateNewSessionKey())
    ]) ?>
<?php endif; ?>
