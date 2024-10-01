<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\community\widgets\icons\WidgetIconAccademyDocument;
use open20\amos\core\icons\AmosIcons;
use open20\amos\community\utilities\CommunityUtil;

\open20\amos\dashboard\assets\DashboardFullsizeAsset::register($this);
$this->params['checkedByDefault'] = false;

/**
 * @var $this \yii\web\View
 * @var $model \open20\amos\community\models\Community
 */
// title not active in layout view_network
//$this->title = AmosCommunity::t('amoscommunity', 'Welcome to the community!');
//if (!is_null($model->parent_id)) {
//    $this->title = AmosCommunity::t('amoscommunity', '#welcome_to_subcommunity');
//}

$module = AmosCommunity::instance();
?>
<div class="actions-dashboard-container community-dashboard-container">
    <?php
    if (!$module->showIconsPluginOnlyAdmin || ($module->showIconsPluginOnlyAdmin && (Yii::$app->user->can('ADMIN') || CommunityUtil::isManagerUser($model, 'id')))) {

        ?>
        <nav>
            <div class="container nop">
                <div class="wrap-plugins">
                    <div id="widgets-icon" class="widgets-icon" role="menu">
                        <?php if ($model->hide_participants == 0 && $model->showParticipantWidget()) { ?>
                            <?php
                            $urlDisplayParticipantsMm = '';
                            $showBox = true;
                            if ($model->context == 'open20\amos\events\models\Event') {
                                $urlDisplayParticipantsMm = "/events/event/participants?communityId={$model->id}";
                                if (method_exists(new \open20\amos\events\utility\EventsUtility(),
                                    'hasPrivilegesLoggedUser')) {
                                    $showBox = false;
                                    $event = \open20\amos\events\models\Event::findOne(['community_id' => $model->id]);
                                    if ($event) {
                                        $showBox = \open20\amos\events\utility\EventsUtility::hasPrivilegesLoggedUser($event);
                                    }
                                } else {
                                    $showBox = true;
                                }
                            } else {
                                $urlDisplayParticipantsMm = "/community/community/participants?communityId={$model->id}";
                            }
                            if (($showBox) && (!($model->context == 'open20\amos\moodle\models\MoodleCourse')))  :
                                ?>

                                <?php
                                if (
                                    \Yii::$app->getModule('community')->showCommunitiesParticipantPluging == true
                                    ||
                                    (\Yii::$app->getModule('community')->showCommunitiesParticipantPluging == false && CommunityUtil::loggedUserIsCommunityManager($model->id))) :
                                    ?>
                                    <div class="square-box"
                                         data-code="open20\amos\admin\widgets\icons\WidgetIconUserProfile">
                                        <div class="square-content item-widget plugin-partecipants">
                                            <a class="dashboard-menu-item" href="<?= $urlDisplayParticipantsMm ?>"
                                               title=<?= AmosCommunity::t('amoscommunity', "#platform_user_list") ?> role="menuitem"
                                               class="sortableOpt1">
                                            <span class="">
                                                <?=
                                                AmosIcons::show('user', [], AmosIcons::IC)
                                                ?>
                                                <span class="icon-dashboard-name pluginName">
                                                    <?=
                                                    AmosCommunity::tHtml('amoscommunity', 'Participants')
                                                    ?>
                                                </span>
                                            </span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php } ?>

                        <?php
                        if (\Yii::$app->getModule('community')->showSubcommunitiesWidget === true && $model->showSubCommunityWidget()) {
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
                        ?>

                        <?php

                        echo \open20\amos\dashboard\widgets\SubDashboardWidget::widget([
                            'model' => $model,
                            'widgets_type' => 'ICON',
                        ]);

                        // Chat widget
                        if(\Yii::$app->getModule('community')->canChat($model)) {
                            echo \open20\amos\comments\widgets\CommentsWidget::widget([
                                'model' => $model,
                                'layoutInverted' => true,
                                'chat' => true
                            ]);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </nav>
        <?php
    }
    ?>
    <?php
    echo \open20\amos\dashboard\widgets\SubDashboardFullsizeWidget::widget([
        'model' => $model,
        'widgets_type' => 'GRAPHIC',
    ]);

    // Links Widget
    if(\Yii::$app->getModule('community')->canLinks($model)) {
        echo \open20\amos\community\widgets\graphics\WidgetGraphicsLinks::widget([
            'community' => $model,
        ]);
    }
    ?>
</div>
