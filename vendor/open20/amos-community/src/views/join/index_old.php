<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ContextMenuWidget;

/**
 * @var $this \yii\web\View
 * @var $model \open20\amos\community\models\Community
 */
$this->title = AmosCommunity::t('amoscommunity', 'Welcome to the community!');
if (!is_null($model->parent_id)) {
    $this->title = AmosCommunity::t('amoscommunity', '#welcome_to_subcommunity');
}

$modelsEnabled = Yii::$app->getModule('cwh')->modelsEnabled;
$viewUrl = '/community/community/view?id=' . $model->id;

$moduleCommunity = Yii::$app->getModule('community');
$fixedCommunityType = (!is_null($moduleCommunity->communityType));

//TODO check why without register this js the confirmation dialog on delete action (context menu widget) does not make any confirmation popup.
\yii\web\YiiAsset::register($this);
//in any case confirmation popup has wrong css. see same popup in te list (index). something wrong with asset registration.

?>

<div class="community-box nop media">
    <?php if ($model->context == Community::className()): ?>
        <?= ContextMenuWidget::widget([
            'model' => $model,
            'actionModify' => "/community/community/update?id=" . $model->id,
            'optionsModify' => [
                'class' => 'community-modify',
            ],
            'actionDelete' => "/community/community/delete?id=" . $model->id,
            'mainDivClasses' => ''
        ]) ?>
    <?php endif; ?>
    <?php
    $url = '/img/img_default.jpg';
    if (!is_null($model->communityLogo)) {
        $url = $model->communityLogo->getUrl('square_large', false, true);
    }
    Yii::$app->imageUtility->methodGetImageUrl = 'getUrl';
    $roundImage = Yii::$app->imageUtility->getRoundImage($model->communityLogo);
    $logo = Html::img($url,
        [
            'class' => $roundImage['class'],
            'style' => "margin-left: " . $roundImage['margin-left'] . "%; margin-top: " . $roundImage['margin-top'] . "%;",
            'alt' => $model->getAttributeLabel('communityLogo')
        ]);
    ?>
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2">
        <div class="container-round-img">
            <?php if ($model->context == Community::className()): ?>
                <?=
                Html::a($logo, $viewUrl,
                    [
                        'title' => AmosCommunity::t('amoscommunity', 'View community'),
                    ]
                )
                ?>
            <?php else: ?>
                <?= $logo ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="media-body">
        <div class="col-sm-12 col-md-8 nop community-title-work">
            <p class="media-heading">
                <?php if (!is_null($model->parent_id)): ?>
                    <?= AmosCommunity::tHtml('amoscommunity', '#working_in_subcommunity'); ?>
                <?php else: ?>
                    <?= AmosCommunity::tHtml('amoscommunity', 'You are working within community:'); ?>
                <?php endif; ?>
            </p>
            <h2 class="media-heading community-title">
                <?php if ($model->context == Community::className()): ?>
                    <?= Html::a($model->name, $viewUrl, ['title' => AmosCommunity::t("amoscommunity", "View community")]) ?>
                <?php else: ?>
                    <?= $model->name ?>
                <?php endif; ?>
            </h2>
        </div>
        <div class="col-sm-12 col-md-8 nop created-by">
            <?php if (!$fixedCommunityType): ?>
                <p class="community-type">
                    <?= AmosCommunity::t('amoscommunity', $model->communityType->name) ?>
                </p>
            <?php endif; ?>
            <p class="community-info">
                <?= AmosCommunity::tHtml('amoscommunity', 'Created by') . ' ' . '<strong>' . $model->createdByUser->profile->__toString() . '</strong>'; ?>
            </p>
        </div>
        <div class="col-sm-12 nop">
            <div class="community-description">
                <?= Yii::$app->getFormatter()->asRaw($model->description) ?>
            </div>
        </div>
    </div>

</div>
</div> <!-- this closes div page-content to allow presence of the dashboard -->
<?php //TODO cercare una soluzione per le aree di lavoro/room ?>

<div class="actions-dashboard-container room-page-content">

    <ul id="widgets-icon" class="bk-sortableIcon plugin-list p-t-25" role="menu">

        <li class="item-widget col-custom" data-code="open20\amos\admin\widgets\icons\WidgetIconUserProfile">
            <a href="/community/community/participants?id=<?= $model->id ?>"
               title=<?= AmosCommunity::t('amoscommunity', "#platform_user_list") ?> role="menuitem"
               class="sortableOpt1">
                <span class="badge"></span>
                <span class="color-primary bk-backgroundIcon color-darkGrey">
                    <span class="dash dash-users"> </span>
                    <span class="icon-dashboard-name pluginName">
                        <?= AmosCommunity::t('amoscommunity', 'Participants') ?>
                    </span>
                </span>
            </a>
        </li>

        <?php
        if (\Yii::$app->getModule('community')->showSubcommunitiesWidget === true) {
            $widgetSubcommunities = Yii::createObject($model->getPluginWidgetClassname());
            echo $widgetSubcommunities::widget();
        }
        if ($model->context == 'open20\amos\projectmanagement\models\Projects') {
            /** @var \open20\amos\core\record\Record $contentObject */
            $contentObject = Yii::createObject(open20\amos\projectmanagement\models\Projects::className());
            $widgetClassname = $contentObject->getPluginWidgetClassname();
            $widget = Yii::createObject($widgetClassname);
            echo $widget::widget();
        }
        echo \open20\amos\dashboard\widgets\SubDashboardWidget::widget([
            'model' => $model,
            'widgets_type' => 'ICON',
        ]);
        ?>
    </ul>
    <div class="clearfix"></div>

    <div class="m-t-30"></div>
    <?=
    \open20\amos\dashboard\widgets\SubDashboardWidget::widget([
        'model' => $model,
        'widgets_type' => 'GRAPHIC',
    ])
    ?>

</div>
