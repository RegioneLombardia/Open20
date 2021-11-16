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
 * Class ShowWidgetIconMoodleLessonsRule
 * @package open20\amos\moodle\rules
 */
class ShowWidgetIconMoodleLessonsRule extends Rule
{
    public $name = 'showWidgetIconMoodleLessons';
    
    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $communityId = MoodleUtility::getCommunityId();

        return !is_null($communityId);
    }
}
