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

        <?php if ($model->hide_participants == 0) { ?>
            <li class="item-widget col-custom" data-code="open20\amos\admin\widgets\icons\WidgetIconUserProfile">
                <a href="/community/community/participants?communityId=<?= $model->id ?>"
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
        <?php } ?>

        <?php
        if ((!empty(\Yii::$app->params['isPoi']) && \Yii::$app->params['isPoi'] == false) && \Yii::$app->getModule('community')->showSubcommunitiesWidget
            === true && (!in_array($model->id, [2751, 2754, 2750, 2772]))) {
            $widgetSubcommunities = Yii::createObject($model->getPluginWidgetClassname());
            echo $widgetSubcommunities::widget();
        }
        if (empty(\Yii::$app->params['isPoi']) && \Yii::$app->getModule('community')->showSubcommunitiesWidget === true) {
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

        // Community accademy POI
        if (isset(Yii::$app->params['isPoi']) && (Yii::$app->params['isPoi'] === true) && ($model->id == 2761)) {
            if (!empty(Yii::$app->getModule('chat')) && (Yii::$app->getModule('chat')->assistanceUserId != Yii::$app->user->id)) {
                /** @var \open20\amos\core\record\Record $contentObject */
                $widgetClassname = \open20\amos\chat\widgets\icons\WidgetIconChatAssistance::className();
                $widget = Yii::createObject($widgetClassname);
                echo $widget::widget();
            }
            /** @var \open20\amos\core\record\Record $contentObject */
            $widgetClassname = WidgetIconAccademyDocument::className();
            $widget = Yii::createObject($widgetClassname);
            echo $widget::widget();
        }

        if (isset(Yii::$app->params['isPoi']) && (Yii::$app->params['isPoi'] === true) && ($model->id == 2795|| $model->id == 2794 || $model->id == 2793 )) {
            /** @var \open20\amos\core\record\Record $contentObject */
            $utility = new \open20\amos\community\utilities\CommunityUtil();
//            $community = \open20\amos\community\models\Community::findOne(\Yii::$app->request->get('id'));
//            $userMm = \open20\amos\community\models\CommunityUserMm::findOne([
//                'community_id' => \Yii::$app->request->get('id'),
//                'user_id' => \Yii::$app->getUser()->getId(),
//                'role' => \open20\amos\community\models\Community::ROLE_COMMUNITY_MANAGER
//            ]);
//
//            if(!empty($userMm)){
//                $widgetClassname1 = open20\amos\sondaggi\widgets\icons\WidgetIconSondaggi::className();
//                $widget1 = Yii::createObject($widgetClassname1);
//            }else {
                $widgetClassname1 = open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggi::className();
                $widget1 = Yii::createObject($widgetClassname1);
//            }

            $widgetClassname4 = \open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className();
            $widget4 = Yii::createObject($widgetClassname4);
            $widgetClassname5 = open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className();
            $widget5 = Yii::createObject($widgetClassname5);
            $widgetClassname6 = \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className();
            $widget6 = Yii::createObject($widgetClassname6);
            echo $widget1::widget();
            echo $widget4::widget();
            echo $widget5::widget();
            echo $widget6::widget();
        }

        if (isset(Yii::$app->params['isPoi']) && (Yii::$app->params['isPoi'] === true) && ($model->id == 2782)) {
            $widgetClassname6 = \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className();
            $widget6 = Yii::createObject($widgetClassname6);
            $widgetClassname4 = \open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className();
            $widget4 = Yii::createObject($widgetClassname4);
            $widgetClassname5 = open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className();
            $widget5 = Yii::createObject($widgetClassname5);
            echo $widget6::widget();
            echo $widget4::widget();
            echo $widget5::widget();
        }

        if (isset(Yii::$app->params['isPoi']) && (Yii::$app->params['isPoi'] === true) && ($model->id == 2772 )) {
            $widgetClassname1 = open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard::className();
            $widgetClassname2 = open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestDashboard::className();
            $widgetClassname3 = open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className();
            $widget1 = Yii::createObject($widgetClassname1);
            $widget2 = Yii::createObject($widgetClassname2);
            $widget3 = Yii::createObject($widgetClassname3);

            echo $widget1::widget();
            echo $widget2::widget();
            echo $widget3::widget();

        }

       if (isset(Yii::$app->params['isPoi']) && (Yii::$app->params['isPoi'] === true) && ($model->id == 2837 )) {
            $widgetClassname1 = \open20\amos\news\widgets\icons\WidgetIconNewsDashboard ::className();
            $widgetClassname2 = \open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className();
            $widget1 = Yii::createObject($widgetClassname1);
            $widget2 = Yii::createObject($widgetClassname2);

            echo $widget1::widget();
            echo $widget2::widget();

        }

        if (isset(Yii::$app->params['isPoi']) && (Yii::$app->params['isPoi'] === true) && (in_array($model->id, [2839]))) {
            $widgetClassname6 = \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className();
            $widget6 = Yii::createObject($widgetClassname6);
            $widgetClassname4 = \open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className();
            $widget4 = Yii::createObject($widgetClassname4);
            $widgetClassname5 = open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className();
            $widget5 = Yii::createObject($widgetClassname5);
            $widgetClassname3 = \open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggi::className();
            $widget3 = Yii::createObject($widgetClassname3);
            echo $widget4::widget();
            echo $widget6::widget();
            echo $widget5::widget();
            echo $widget3::widget();
        }

        if (isset(Yii::$app->params['isPoi']) && (Yii::$app->params['isPoi'] === true) && (in_array($model->id, [2831 ]))) {
            $widgetClassname6 = \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className();
            $widget6 = Yii::createObject($widgetClassname6);
            $widgetClassname4 = \open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className();
            $widget4 = Yii::createObject($widgetClassname4);
            $widgetClassname5 = open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className();
            $widget5 = Yii::createObject($widgetClassname5);
            $widgetClassname3 = \backend\widgets\icons\WidgetIconSondaggiStatiAvanzamento::className();
            $widget3 = Yii::createObject($widgetClassname3);
            echo $widget4::widget();
            echo $widget6::widget();
            echo $widget5::widget();
            echo $widget3::widget();
        }

        if (isset(Yii::$app->params['isPoi']) && (Yii::$app->params['isPoi'] === true) && (in_array($model->id ,[2814,2815,2816,2817,2818,2819,2820,2840,  2826,2828,2830]))) {
            $widgetClassname6 = \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className();
            $widget6 = Yii::createObject($widgetClassname6);
            $widgetClassname4 = \open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className();
            $widget4 = Yii::createObject($widgetClassname4);
            $widgetClassname5 = open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className();
            $widget5 = Yii::createObject($widgetClassname5);
            echo $widget5::widget();
            echo $widget4::widget();
            echo $widget6::widget();

        }




        if (!empty(\Yii::$app->params['isPoi']) && \Yii::$app->params['isPoi'] == true && in_array($model->id,
                [2750, 2761, 2769, 2772, 2795, 2794, 2793, 2782, 2837,  2814,2815,2816,2817,2818,2819,2820,2840, 2839 , 2831,2826,2828,2830])) {

        } else {
            echo \open20\amos\dashboard\widgets\SubDashboardWidget::widget([
                'model' => $model,
                'widgets_type' => 'ICON',
            ]);
        }
        ?>
    </ul>
    <div class="clearfix"></div>

    <div class="m-t-30"></div>
    <?php
    if (!empty(\Yii::$app->params['isPoi']) && \Yii::$app->params['isPoi'] == true && in_array($model->id, [2761])) {
        \open20\amos\dashboard\assets\SubDashboardAsset::register(\Yii::$app->getView());
        $widgetClassname1 = \open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews::className();
        $widget1 = Yii::createObject($widgetClassname1);
        echo '<div id="bk-pluginGrafici" class="sub-dashboard-graphics">' .
            '<div class="graphics-dashboard-container">' .
            '<div id="widgets-graphic" class="widgets-graphic-sortable">' .
            '<div class="grid">' .
            '<div class="grid-sizer"></div>';
        echo '<div data-code="' . $widget1::classname() . '" data-module-name>' . $widget1::widget() . '</div>';
        echo '</div>' .
            '</div>' .
            '</div>' .
            '</div>';
    } else if (!empty(\Yii::$app->params['isPoi']) && \Yii::$app->params['isPoi'] == true && in_array($model->id, [2750])) {
        \open20\amos\dashboard\assets\SubDashboardAsset::register(\Yii::$app->getView());

        $allWidget = '';

        echo '<div id="bk-pluginGrafici" class="sub-dashboard-graphics">' .
            '<div class="graphics-dashboard-container">' .
            '<div id="widgets-graphic" class="widgets-graphic-sortable">' .
            '<div class="grid">' .
            '<div class="grid-sizer"></div>';

        $widgetClassname1 = \openinnovation\landing\widgets\graphics\WidgetGraphicDownloadTicket::className();
        $widget1 = Yii::createObject($widgetClassname1);
        $widgetClassname2 = \open20\amos\chat\widgets\graphics\WidgetGraphicChatAssistance::className();
        $widget2 = Yii::createObject($widgetClassname2);
        $widgetClassname3 = \open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews::className();
        $widget3 = Yii::createObject($widgetClassname3);
        $widgetClassname4 = \open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti::className();
        $widget4 = Yii::createObject($widgetClassname4);

        if($widget1->isVisible()){
            $allWidget .= '<div data-code="' . $widget1::classname() . '" data-module-name>' . $widget1::widget() . '</div>';
            $allWidget .= '<div data-code="' . $widget3::classname() . '" data-module-name>' . $widget3::widget() . '</div>';
            $allWidget .= '<div data-code="' . $widget2::classname() . '" data-module-name>' . $widget2::widget() . '</div>';
            $allWidget .= '<div data-code="' . $widget4::classname() . '" data-module-name>' . $widget4::widget() . '</div>';
        } else {
            $allWidget .= '<div data-code="' . $widget2::classname() . '" data-module-name>' . $widget2::widget() . '</div>';
            $allWidget .= '<div data-code="' . $widget4::classname() . '" data-module-name>' . $widget4::widget() . '</div>';
            $allWidget .= '<div data-code="' . $widget3::classname() . '" data-module-name>' . $widget3::widget() . '</div>';
        }

        echo $allWidget;

        echo '</div>' .
            '</div>' .
            '</div>' .
            '</div>';
    }
    else if (!empty(\Yii::$app->params['isPoi']) && \Yii::$app->params['isPoi'] == true && in_array($model->id, [2769])) {
        \open20\amos\dashboard\assets\SubDashboardAsset::register(\Yii::$app->getView());
        $widgetClassname1 = \open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews::className();
        $widget1 = Yii::createObject($widgetClassname1);
        $widgetClassname2 = \open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti::className();
        $widget2 = Yii::createObject($widgetClassname2);
        echo '<div id="bk-pluginGrafici" class="sub-dashboard-graphics">' .
            '<div class="graphics-dashboard-container">' .
            '<div id="widgets-graphic" class="widgets-graphic-sortable">' .
            '<div class="grid">' .
            '<div class="grid-sizer"></div>';
        echo '<div data-code="' . $widget1::classname() . '" data-module-name>' . $widget1::widget() . '</div>';
        echo '<div data-code="' . $widget2::classname() . '" data-module-name>' . $widget2::widget() . '</div>';
        echo '</div>' .
            '</div>' .
            '</div>' .
            '</div>';
    }
    else if (!empty(\Yii::$app->params['isPoi']) && \Yii::$app->params['isPoi'] == true && in_array($model->id, [2772])) {
        \open20\amos\dashboard\assets\SubDashboardAsset::register(\Yii::$app->getView());
        $widgetClassname1 = \openinnovation\landing\widgets\graphics\WidgetGraphicCountdown::className();
        $widget1 = Yii::createObject($widgetClassname1);
//        $widgetClassname2 = \openinnovation\landing\widgets\graphics\WidgetGraphicLiveChat::className();
//        $widget2 = Yii::createObject($widgetClassname2);
        echo '<div id="bk-pluginGrafici" class="sub-dashboard-graphics">' .
            '<div class="graphics-dashboard-container">' .
            '<div id="widgets-graphic" class="widgets-graphic-sortable">' .
            '<div class="grid">' .
            '<div class="grid-sizer"></div>';
        echo '<div data-code="' . $widget1::classname() . '" data-module-name>' . $widget1::widget() . '</div>';
//        echo '<div data-code="' . $widget2::classname() . '" data-module-name>' . $widget2::widget(['url' => '/community/join/live-chat']) . '</div>';
        echo '</div>' .
            '</div>' .
            '</div>' .
            '</div>';
    }
    else if (!empty(\Yii::$app->params['isPoi']) && \Yii::$app->params['isPoi'] == true && in_array($model->id, [ 2795, 2794, 2793])) {
        \open20\amos\dashboard\assets\SubDashboardAsset::register(\Yii::$app->getView());
        $allWidget = '';

        echo '<div id="bk-pluginGrafici" class="sub-dashboard-graphics">' .
            '<div class="graphics-dashboard-container">' .
            '<div id="widgets-graphic" class="widgets-graphic-sortable">' .
            '<div class="grid">' .
            '<div class="grid-sizer"></div>';


        $widgetClassname2 = \open20\amos\chat\widgets\graphics\WidgetGraphicChatAssistance::className();
        $widget2 = Yii::createObject($widgetClassname2);
        $widgetClassname3 = \open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews::className();
        $widget3 = Yii::createObject($widgetClassname3);
        $widgetClassname4 = open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni::className();
        $widget4 = Yii::createObject($widgetClassname4);
        $widgetClassname5 = open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti::className();
        $widget5 = Yii::createObject($widgetClassname5);


            $allWidget .= '<div data-code="' . $widget2::classname() . '" data-module-name>' . $widget2::widget() . '</div>';
            $allWidget .= '<div data-code="' . $widget3::classname() . '" data-module-name>' . $widget3::widget() . '</div>';
            $allWidget .= '<div data-code="' . $widget4::classname() . '" data-module-name>' . $widget4::widget() . '</div>';
            $allWidget .= '<div data-code="' . $widget5::classname() . '" data-module-name>' . $widget5::widget() . '</div>';


        echo $allWidget;

        echo '</div>' .
            '</div>' .
            '</div>' .
            '</div>';
    }
    else if (!empty(\Yii::$app->params['isPoi']) && \Yii::$app->params['isPoi'] == true && in_array($model->id, [2837])) {
        \open20\amos\dashboard\assets\SubDashboardAsset::register(\Yii::$app->getView());
        $allWidget = '';

        echo '<div id="bk-pluginGrafici" class="sub-dashboard-graphics">' .
            '<div class="graphics-dashboard-container">' .
            '<div id="widgets-graphic" class="widgets-graphic-sortable">' .
            '<div class="grid">' .
            '<div class="grid-sizer"></div>';

        $widgetClassname2 = \open20\amos\chat\widgets\graphics\WidgetGraphicChatAssistance::className();
        $widget2 = Yii::createObject($widgetClassname2);
        $widgetClassname3 = \open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews::className();
        $widget3 = Yii::createObject($widgetClassname3);
        $widgetClassname5 = open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti::className();
        $widget5 = Yii::createObject($widgetClassname5);

        $allWidget .= '<div data-code="' . $widget2::classname() . '" data-module-name>' . $widget2::widget() . '</div>';
        $allWidget .= '<div data-code="' . $widget3::classname() . '" data-module-name>' . $widget3::widget() . '</div>';
        $allWidget .= '<div data-code="' . $widget5::classname() . '" data-module-name>' . $widget5::widget() . '</div>';
        echo $allWidget;

        echo '</div>' .
            '</div>' .
            '</div>' .
            '</div>';
    }
    else if (!empty(\Yii::$app->params['isPoi']) && \Yii::$app->params['isPoi'] == true && in_array($model->id, [2839,   2814,2815,2816,2817,2818,2819,2820,2840,  2831,2826,2828,2830])) {
        \open20\amos\dashboard\assets\SubDashboardAsset::register(\Yii::$app->getView());
        $allWidget = '';

        echo '<div id="bk-pluginGrafici" class="sub-dashboard-graphics">' .
            '<div class="graphics-dashboard-container">' .
            '<div id="widgets-graphic" class="widgets-graphic-sortable">' .
            '<div class="grid">' .
            '<div class="grid-sizer"></div>';

        $widgetClassname2 =  open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni::className();
        $widget2 = Yii::createObject($widgetClassname2);
        $widgetClassname3 = \open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews::className();
        $widget3 = Yii::createObject($widgetClassname3);
        $widgetClassname5 = open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti::className();
        $widget5 = Yii::createObject($widgetClassname5);

        $allWidget .= '<div data-code="' . $widget2::classname() . '" data-module-name>' . $widget2::widget() . '</div>';
        $allWidget .= '<div data-code="' . $widget3::classname() . '" data-module-name>' . $widget3::widget() . '</div>';
        $allWidget .= '<div data-code="' . $widget5::classname() . '" data-module-name>' . $widget5::widget() . '</div>';
        echo $allWidget;

        echo '</div>' .
            '</div>' .
            '</div>' .
            '</div>';
    }
    else {
        echo \open20\amos\dashboard\widgets\SubDashboardWidget::widget([
            'model' => $model,
            'widgets_type' => 'GRAPHIC',
        ]);
    }
    ?>

</div>
