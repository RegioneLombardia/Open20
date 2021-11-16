<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\rules
 * @category   CategoryName
 */

namespace open20\amos\admin\rules;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use Yii;
use yii\rbac\Rule;

/**
 * Class ValidatedBasicUserRule
 * @package open20\amos\admin\rules
 */
class ValidatedBasicUserRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'validatedBasicUser';

    /**
     * @inheritdoc
     */
    public function execute($loggedUserId, $item, $params)
    {
        /** @var UserProfile $loggedUser */
        $loggedUser = \Yii::$app->getUser()->identity->profile;
        $adminModule = \Yii::$app->getModule(AmosAdmin::getModuleName());
        $communityModule = \Yii::$app->getModule('communty');
        $cwhModule = \Yii::$app->getModule('cwh');
        $scope = (!is_null($cwhModule) ? $cwhModule->getCwhScope() : []);
        
        if (($adminModule->createContentInMyOwnCommunityOnly === true) && (isset($scope['community']) && !(empty($communityModule)))) {
            if (isset($scope['community']) && !(empty($communityModule))) {
                $myOwnCommunities = $communityModule->getCommunitiesByUserId(Yii::$app->getUser()->getId(), true);

                return (in_array($scope['community'], $myOwnCommunities));
            }

            return false;
        }

        return ($loggedUser->validato_almeno_una_volta == true);
    }
}
