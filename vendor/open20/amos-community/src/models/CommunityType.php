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

/**
 * Class CommunityType
 * This is the model class for table "community_types".
 * @package open20\amos\community\models
 */
class CommunityType extends \open20\amos\community\models\base\CommunityType
{
    /**
     * Constants for ID of the three community types
     */
    const COMMUNITY_TYPE_OPEN = 1;
    const COMMUNITY_TYPE_PRIVATE = 2;
    const COMMUNITY_TYPE_CLOSED = 3;
}
