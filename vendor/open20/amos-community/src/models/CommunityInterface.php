<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\models
 * @category   CategoryName
 */

namespace open20\amos\community\models;

/**
 * Class CommunityInterface
 *
 * @property \open20\amos\community\models\Community $community
 * @property int $communityId
 *
 * @package open20\amos\community\models
 */
interface CommunityInterface
{
    /**
     * This method return the ActiveQuery to search the model related community.
     * @return \yii\db\ActiveQuery
     */
    public function getCommunity();
    
    /**
     * Getter method for community_id field.
     * @return int
     */
    public function getCommunityId();
    
    /**
     * Setter method for community_id field.
     * @param int $communityId
     */
    public function setCommunityId($communityId);
}
