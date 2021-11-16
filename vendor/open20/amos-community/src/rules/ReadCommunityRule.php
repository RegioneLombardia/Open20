<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\rules
 * @category   CategoryName
 */

namespace open20\amos\community\rules;
use open20\amos\admin\models\UserProfile;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityType;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;

/**
 * Class UpdateOwnExprOfIntRule
 * @package open20\amos\partnershipprofiles\rules
 */
class ReadCommunityRule extends \open20\amos\core\rules\BasicContentRule
{
    public $name = 'ReadCommunity';

    /**
     * Rule to Read Community
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var Community $model */
        if($model->community_type_id == CommunityType::COMMUNITY_TYPE_OPEN || $model->community_type_id == CommunityType::COMMUNITY_TYPE_PRIVATE){
            return true;
        } elseif($model->community_type_id == CommunityType::COMMUNITY_TYPE_CLOSED){
            $communityUserMm = CommunityUserMm::find()->andWhere(['community_id' => $model->id])->andWhere(['user_id' => $user])->one();
            if(!empty($communityUserMm)) {
                return true;
            }
        }
        return false;
    }
}
