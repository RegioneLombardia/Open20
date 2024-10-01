<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\partnership-profiles\boxes
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\partnershipprofiles\controllers\PartnershipProfilesController;
use open20\amos\partnershipprofiles\Module;
use open20\amos\partnershipprofiles\rules\ReadAllExprOfIntRule;
use open20\amos\partnershipprofiles\widgets\ExpressYourInterestWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\partnershipprofiles\models\PartnershipProfiles $model
 */

$statesCounter = $model->getExpressionsOfInterestStatesCounter();

/** @var PartnershipProfilesController $appController */
$appController = Yii::$app->controller;

?>
<div class="col-md-5 col-xs-12">
    <h4 class="title m-t-0 m-b-0">
        <?= $statesCounter['notdraft'] ?>
        
        <?= (($statesCounter['notdraft'] == 1) ?
            Module::tHtml('amospartnershipprofiles', '#expression_of_interest_sidebar') :
            Module::tHtml('amospartnershipprofiles', '#expressions_of_interest_sidebar')
        ) ?>
       
        <?php if (\Yii::$app->user->can(ReadAllExprOfIntRule::className(), ['model' => $model])): ?>
            <?= '(' . Html::a(
                Module::tHtml('amospartnershipprofiles', 'view all'),
                ['/partnershipprofiles/expressions-of-interest/all', 'partnership_profile_id' => $model->id],
                ['class' => ' ']
            ) . ')'; ?>
           
        <?php endif; ?>
    </h4>
    <div class="container-sidebar">
        <div class="box">
            <div class="media">
                <div class="media-left">
                    <p class="h1 number-participants"><?= $statesCounter['active'] ?></p>
                </div>
                <div class="media-body" style="border-left: 1px solid #000;padding-left: 15px;">
                    <h4 class="media-heading"><?= Module::tHtml('amospartnershipprofiles', '#active_sidebar') ?></h4>
                    <?= Module::tHtml('amospartnershipprofiles', 'Number of submitted expressions of interest') ?>
                </div>
            </div>

            <div class="media">
                <div class="media-left">
                    <p class="h1 number-participants"><?= $statesCounter['tovalidate'] ?></p>
                </div>
                <div class="media-body" style="border-left: 1px solid #000;padding-left: 15px;">
                    <h4 class="media-heading"><?= Module::tHtml('amospartnershipprofiles', '#in_assessment_sidebar') ?></h4>
                    <?= Module::tHtml('amospartnershipprofiles', 'Number of expressions of interest in assessment') ?>
                </div>
            </div>

            <div class="media">
                <div class="media-left">
                    <p class="h1 number-participants"><?= $statesCounter['relevant'] ?></p>
                </div>
                <div class="media-body" style="border-left: 1px solid #000;padding-left: 15px;">
                    <h4 class="media-heading"><?= Module::tHtml('amospartnershipprofiles', '#relevant_sidebar') ?></h4>
                    <?= Module::tHtml('amospartnershipprofiles', 'Number of relevant expressions of interest') ?>
                </div>
            </div>

            <div class="media">
                <div class="media-left">
                    <p class="h1 number-participants"><?= $statesCounter['rejected'] ?></p>
                </div>
                <div class="media-body" style="border-left: 1px solid #000;padding-left: 15px;">
                    <h4 class="media-heading"><?= Module::tHtml('amospartnershipprofiles', '#rejected_sidebar') ?></h4>
                    <?= Module::tHtml('amospartnershipprofiles', 'Number of rejected expressions of interest') ?>
                </div>
            </div>
        </div>
        <?php
        $widgetParams = ['model' => $model];
        if (isset($ownInterestPartnershipProfileIds)) {
            $widgetParams['allowedPartnershipProfileIds'] = $ownInterestPartnershipProfileIds;
        }
        ?>
        <?= ExpressYourInterestWidget::widget($widgetParams); ?>
        <?php if ($appController->viewCreateProjectGroupBtn($model)): ?>
            <div class="footer_sidebar col-xs-12 text-right">
                <?= Html::a(Module::t('amospartnershipprofiles', 'Create project group'), ['/partnershipprofiles/partnership-profiles/create-project-group', 'id' => $model->id], ['class' => 'btn btn-navigation-primary']); ?>
            </div>
        <?php endif; ?>
        <?php if ($appController->viewAccessProjectGroupBtn($model)): ?>
            <div class="footer_sidebar col-xs-12 text-right">
                <?= Html::a(Module::t('amospartnershipprofiles', 'Access the project group'), ['/community/join/index', 'id' => $model->community_id], ['class' => 'btn btn-navigation-primary']); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
