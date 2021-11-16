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

use open20\amos\moodle\utility\MoodleUtility;
use open20\amos\moodle\models\MoodleCourse;
use Yii;
use yii\rbac\Rule;

/**
 * Class ShowWidgetIconMoodleBadgesRule
 * @package open20\amos\moodle\rules
 */
class ShowWidgetIconMoodleBadgesRule extends Rule
{
    public $name = 'showWidgetIconMoodleBadges';
    
    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $communityId = MoodleUtility::getCommunityId();

        if (!is_null($communityId)) {
            $course = MoodleCourse::findOne([
                        'community_id' => $communityId,   // il corso associato a quella community
            ]);
            $boolFlag = $course->hasBadges();
            //pr($course->toArray(), 'ShowWidgetIconMoodleBadgesRule $course: '.$boolFlag.'.');exit;
            return $course->hasBadges();
        }
        return false;
    }
}
