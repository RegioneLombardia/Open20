<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\rules
 * @category   CategoryName
 */

namespace open2\amos\ticket\rules;

use open20\amos\community\models\Community;
use open20\amos\core\rules\DefaultOwnContentRule;

/**
 * Class TicketManagerInCommunityRoleRule
 * @package open2\amos\ticket\rules
 */
class TicketManagerInCommunityRoleRule extends DefaultOwnContentRule
{
    /**
     * @inheritdoc
     */
    public $name = 'ticketManagerInCommunityRole';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        $moduleCommunity = \Yii::$app->getModule('community');
        if (isset($moduleCwh) && isset($moduleCommunity) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                $community = Community::findOne(['id' => $scope['community']]);
                if (!empty($community)) {
                    return $community->isCommunityManager($user);
                }
            }
        }
        return false;
    }
}
