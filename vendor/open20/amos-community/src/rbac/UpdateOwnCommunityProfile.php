<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */
namespace open20\amos\community\rbac;

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\core\user\User;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Class UpdateOwnUserProfile
 * @package open20\amos\community\rbac
 */
class UpdateOwnCommunityProfile extends Rule
{
    public $name = 'isYourCommunityProfile';
    public $description = '';

    public function execute($user, $item, $params)
    {
        $post = \Yii::$app->getRequest()->post();
        $get = \Yii::$app->getRequest()->get();

        $userProfileId = User::findOne($user)->getProfile()->id;
        if (isset($get['id']) && $userProfileId) {
            return true;
        } elseif (isset($post['id']) && $userProfileId) {
            return true;
        }

        return false;
    }
}
