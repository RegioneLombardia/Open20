<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\rules
 * @category   CategoryName
 */

namespace open20\amos\community\rules;

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\cwh\AmosCwh;
use yii\rbac\Rule;
use Yii;

class WidgetIconBookmarksRule extends Rule
{
    public $name = 'WidgetIconBookmarksRule';

    public function execute($user, $item, $params)
    {
        /** @var $moduleCommunity AmosCommunity */
        $moduleCommunity = Yii::$app->getModule('community');
        /** @var $moduleCwh AmosCwh */
        $moduleCwh = Yii::$app->getModule('cwh');
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                $communityId = $scope['community'];
            }
        }

        if ($communityId){ // se esiste community verifico le condizioni della rule
            $community = Community::findOne($communityId);
            return $moduleCommunity->canLinks($community);
        }
        return false;
    }
}