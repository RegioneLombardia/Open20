<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\moodle\rules\ShowWidgetIconMoodleLessonsRule;

/**
 * Class m180123_163224_update_auth_item_moodle_lessons
 */
class m180123_163224_update_auth_item_moodle_lessons extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleLessons::className(),
                'update' => true,
                'newValues' => [
                    'ruleName' => ShowWidgetIconMoodleLessonsRule::className()
                ],
                'oldValues' => [
                    'ruleName' => null
                ]
            ]
        ];
    }
}
