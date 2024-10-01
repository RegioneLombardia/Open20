<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m171214_163016_add_auth_item_moodle_badges
 */
class m171214_163016_add_auth_item_moodle_badges extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleBadges::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconMoodleBadges',
                'ruleName' => null,
                'parent' => ['ADMIN', 'MOODLE_ADMIN', 'MOODLE_STUDENT']
            ]
        ];
    }
}
