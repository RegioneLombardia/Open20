<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\moodle\rules\ShowWidgetIconMoodleRule;

/**
 * Class m180123_163224_update_auth_item_moodle
 */
class m180123_163224_update_auth_item_moodle extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\moodle\widgets\icons\WidgetIconMoodle::className(),
                'update' => true,
                'newValues' => [
                    'ruleName' => ShowWidgetIconMoodleRule::className()
                ],
                'oldValues' => [
                    'ruleName' => null
                ]
            ]
        ];
    }
}
