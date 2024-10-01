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

use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\utilities\CommunityUtil;
use yii\rbac\Rule;
use Yii;

class UserIsNotCommunityReaderBookmarksRule extends Rule
{
    public $name = 'userIsNotCommunityReaderBookmarksRule';

    public function execute($user, $item, $params)
    {
        $moduleCwh = Yii::$app->getModule('cwh');
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                $communityId = $scope['community'];
            }
        }

        if (Yii::$app->getModule('community')){ // se esiste community verifico le condizioni della rule
            return !(isset($communityId) && CommunityUtil::getRole($communityId) == CommunityUserMm::ROLE_READER);
        }
        return true;
    }
}