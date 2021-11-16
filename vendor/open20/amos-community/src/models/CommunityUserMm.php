<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\models;
use yii\db\ActiveQuery;

/**
 * Class CommunityUserMm
 * This is the model class for table "community_user_mm".
 * @package backend\modules\corsi\models
 */
class CommunityUserMm extends \open20\amos\community\models\base\CommunityUserMm
{

    /**
     * Gets the list of community manager email address for a specific community
     *
     * @param integer $communityId The Id of community to search community/user relations
     * @return array $emailArray The array containing community manager email
     */
    public function getCommunityManagerMailList($communityId){

        /** @var ActiveQuery $queryManagers */
        $community = Community::findOne($communityId);
        $callingModule = \Yii::createObject($community->context);
        $managerRole = $callingModule->getManagerRole();
        $queryManagers = CommunityUserMm::find()->andWhere([
            'community_id' => $communityId,
            'role' => $managerRole
        ]);
        $queryManagers->innerJoin('user', 'user_id = user.id');
        $queryManagers->select('user.email');
        $emailArray = $queryManagers->column();
        return $emailArray;
    }

}
