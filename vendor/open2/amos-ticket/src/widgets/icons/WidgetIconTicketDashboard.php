<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\widgets\icons
 * @category   CategoryName
 */

namespace open2\amos\ticket\widgets\icons;

use open20\amos\community\models\Community;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use open2\amos\ticket\AmosTicket;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconTicketDashboard
 * @package open2\amos\ticket\widgets\icons
 */
class WidgetIconTicketDashboard extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $scopeName = '';
        $communityName = $this->getCommunityName();
        if (!empty($communityName)) {
            $scopeName = ' ' . $communityName;
        }
        
        $this->setLabel(AmosTicket::t('amosticket', '#widget_icon_ticket_dashboard_label'));
        $this->setDescription(AmosTicket::t('amosticket', '#widget_icon_ticket_dashboard_description') . ' ' . $scopeName);

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-lightPrimary'
        ];

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('assistenza');
            $paramsClassSpan = [];
        } else {
            $this->setIconFramework('dash');
            $this->setIcon('feed');
        }

        $this->setUrl(Yii::$app->urlManager->createUrl(['/ticket']));
        $this->setModuleName('ticket');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
    }

    /**
     * 
     * @return type
     */
    private function getCommunityName()
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        $moduleCommunity = \Yii::$app->getModule('community');
        if (isset($moduleCwh) && isset($moduleCommunity) && !empty($moduleCwh->getCwhScope())) {

            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                $community = Community::findOne(['id' => $scope['community']]);
                if (!empty($community)) {
                    return $community->name;
                }
            }
        }

        return null;
    }

}
