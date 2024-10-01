<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\rules
 * @category   CategoryName
 */

namespace open20\video\rules;


use open20\amos\community\utilities\CommunityUtil;
use open20\video\Module;
use yii\rbac\Rule;
use Yii;

class UserManagementVideoRule extends Rule
{
    public $name = 'userManagementVideoRule';

    public function execute($user, $item, $params)
    {
        $result = true;
        /** @var Module $moduleFormazione */
        $moduleVideo = \Yii::$app->getModule('video');
        if($moduleVideo->enabledEnvironment != Module::ENABLED_ENV_TYPE['PLATFORM']) {
            $moduleCwh = Yii::$app->getModule('cwh');
            if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
                $scope = $moduleCwh->getCwhScope();
                if (isset($scope['community'])) {
                    $communityId = $scope['community'];
                }
            }
            $result = $result && CommunityUtil::isLoggedCommunityManager();
        }
        if($moduleVideo->enabledEnvironment == Module::ENABLED_ENV_TYPE['COMMUNITY']) {
            $result = $result && Yii::$app->user->can('SUPER_USER');
        }
        return $result;
    }
}