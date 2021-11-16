<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle\rules
 * @category   CategoryName
 */

namespace open20\amos\moodle\rules;

use Yii;
use yii\rbac\Rule;
use open20\amos\moodle\utility\MoodleUtility;

/**
 * Class ShowWidgetIconMoodleRule
 * @package open20\amos\moodle\rules
 */
class ShowWidgetIconMoodleRule extends Rule
{
    public $name = 'showWidgetIconMoodle';
    
    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $communityId = MoodleUtility::getCommunityId();
        if(!is_null($communityId)) {
            $categoria = MoodleUtility::getCommunityCategory();
            return !is_null($categoria) && $categoria->visible; // la categoria deve essere visisbile su moodle
        }
        return true;
     
    }
}
