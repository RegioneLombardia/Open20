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
use open20\amos\community\widgets\icons\WidgetIconAccademyDocument;

/**
 * @var $this \yii\web\View
 * @var $model \open20\amos\community\models\Community
 */
// title not active in layout view_network
//$this->title = AmosCommunity::t('amoscommunity', 'Welcome to the community!');
//if (!is_null($model->parent_id)) {
//    $this->title = AmosCommunity::t('amoscommunity', '#welcome_to_subcommunity');
//}
?>

<div class="actions-dashboard-container community-dashboard-container">

    <ul id="widgets-icon" class="bk-sortableIcon plugin-list p-t-25" role="menu">

        <?php if ($model->hide_participants == 0 && $model->showParticipantWidget()) { ?>
            <?php
                $urlDisplayParticipantsMm = '';
                $showWidget = true;
                if ($model->context == 'open20\amos\events\models\Event') {
                    $urlDisplayParticipantsMm = "/events/event/participants?communityId={$model->id}";
                    if(method_exists(new \open20\amos\events\utility\EventsUtility(), 'hasPrivilegesLoggedUser')) {
                        $showWidget = false;
                        $event = \open20\amos\events\models\Event::findOne(['community_id' => $model->id]);
                        if($event) {
                            $showWidget = \open20\amos\events\utility\EventsUtility::hasPrivilegesLoggedUser($event);
                        }
                    } else {
                        $showWidget = true;
                    }
                } else {
                    $urlDisplayParticipantsMm = "/community/community/participants?communityId={$model->id}";
                }
                if($showWidget) :
            ?>
            <li class="item-widget col-custom" data-code="open20\amos\admin\widgets\icons\WidgetIconUserProfile">
                <a href="<?= $urlDisplayParticipantsMm; ?>"
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
            <?php endif; ?>
        <?php }
        if (\Yii::$app->getModule('community')->showSubcommunitiesWidget === true || $model->showSubCommunityWidget()) {
            $widgetSubcommunities = Yii::createObject($model->getPluginWidgetClassname());
            echo $widgetSubcommunities::widget();
        }
        if ($model->context == 'open20\amos\projectmanagement\models\Projects') {
            /** @var \open20\amos\core\record\Record $contentObject */
            $contentObject = Yii::createObject(open20\amos\projectmanagement\models\Projects::className());
            $widgetClassname = $contentObject->getPluginWidgetClassname();
            $widget = Yii::createObject($widgetClassname);
            echo $widget::widget();
        }?>




        <?php
            echo \open20\amos\dashboard\widgets\SubDashboardWidget::widget([
                'model' => $model,
                'widgets_type' => 'ICON',
            ]);
        ?>
    </ul>
    <div class="clearfix"></div>


    <?php
        echo \open20\amos\dashboard\widgets\SubDashboardWidget::widget([
            'model' => $model,
            'widgets_type' => 'GRAPHIC',
        ]);
    ?>

</div>
