<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m171215_104110_add_auth_item_moodle_lessons
 */
class m171215_104110_add_auth_item_moodle_lessons extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleLessons::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconMoodleLessons',
                'ruleName' => null,
                'parent' => ['ADMIN', 'MOODLE_ADMIN', 'MOODLE_STUDENT']
            ]
        ];
    }
}
