<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 27/10/2020
 * Time: 10:52
 */

namespace amos\podcast\utility;


use open20\amos\community\utilities\CommunityUtil;

class PodcastUtility
{

    /**
     * @return bool
     */
    public static function isManagerCommunity()
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        $community_id = null;
        isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;
        if ($scope && $scope['community']) {
            $community_id = $scope['community'];
        }
        $isManager = false;
        if ($community_id) {
            $isManager = CommunityUtil::loggedUserIsCommunityManager($community_id);
        }
        return $isManager;
    }
}