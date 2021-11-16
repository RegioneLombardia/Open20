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
use open20\amos\community\widgets\JoinCommunityWidget;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\notificationmanager\forms\NewsWidget;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\user\User;

/**
 * @var \open20\amos\community\models\Community $model
 */
$communityModule    = Yii::$app->getModule('community');
$fixedCommunityType = !is_null($communityModule->communityType);
$bypassWorkflow     = $communityModule->forceWorkflow($model);

$loggedUserId  = Yii::$app->getUser()->getId();
$userCommunity = CommunityUserMm::findOne(['community_id' => $model->id, 'user_id' => $loggedUserId]);
$userProfile   = User::findOne($loggedUserId)->getProfile();
if (!empty($userProfile) && $userProfile->validato_almeno_una_volta && !is_null($userCommunity) && !in_array(
    $userCommunity->status,
    [CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER, CommunityUserMm::STATUS_WAITING_OK_USER]
)) {
    $viewUrl = '/community/join?id=' . $model->id;
} else {
    $viewUrl = '/community/community/view?id=' . $model->id;
}
?>

<div class="card-container community-card-container col-xs-12 nop">
    <div class="col-xs-12 nop icon-header">
        <?php /*
        ContextMenuWidget::widget([
            'model' => $model,
            'actionModify' => "/community/community/update?id=".$model->id,
            'actionDelete' => "/community/community/delete?id=".$model->id,
            'mainDivClasses' => '',
            'optionsDelete' => ['class' => 'delete-community-btn']
        ]);*/
        ?>
        <?=
            $newsWidget = NewsWidget::widget([
                'model' => $model,
                'css_class' => 'badge'
            ])
        ?>
        <div class="community-image">
            <?php
            $url        = '/img/img_default.jpg';
            if (!is_null($model->communityLogo)) {
                $url = $model->communityLogo->getUrl('item_community', false, true);
            }
            $logo       = Html::img(
                $url,
                [
                    'class' => 'img-responsive',
                    'alt' => $model->getAttributeLabel('communityLogo')
                ]
            );
            ?>
            <?= Html::a($logo, $viewUrl, ['title' => $model->name]); ?>
        </div>
    </div>
    <div class="col-xs-12 nop icon-body">
        <h3 class="title">
            <?=
                Html::a(
                    $model->name,
                    $viewUrl,
                    ['title' => AmosCommunity::t('amoscommunity', '#icon_title_link') . ' ' . $model->name]
                );
            ?>
        </h3>
    </div>
    <div class="col-xs-12 icon-footer">
        <?php if (!$fixedCommunityType) : ?>
            <span class="badge category<?= $model->communityType ?>"><?= $model->getCommunityTypeName() ?></span>
        <?php endif; ?>
        <?php /*
        $accessType = '';

        if (!$fixedCommunityType) {
            $accessType = AmosCommunity::t('amoscommunity', 'Access type: ').AmosCommunity::t('amoscommunity',
                    $model->getCommunityTypeName());
        }

        $content = '';
        $content .= Html::tag('p', $accessType);

        if (!empty($accessType)) {
            echo Html::tag('div', AmosIcons::show('info-circle', [], 'dash'),
                [
                'class' => 'amos-tooltip pull-left',
                'data-toggle' => 'tooltip',
                'data-html' => 'true',
                'title' => $content]
            );
        } */
        ?>
    </div>
</div>