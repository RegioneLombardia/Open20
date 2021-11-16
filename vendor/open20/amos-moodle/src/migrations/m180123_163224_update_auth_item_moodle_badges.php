<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\moodle\rules\ShowWidgetIconMoodleBadgesRule;

/**
 * Class m180123_163224_update_auth_item_moodle_badges
 */
class m180123_163224_update_auth_item_moodle_badges extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleBadges::className(),
                'update' => true,
                'newValues' => [
                    'ruleName' => ShowWidgetIconMoodleBadgesRule::className()
                ],
                'oldValues' => [
                    'ruleName' => null
                ]
            ]
        ];
    }
}
