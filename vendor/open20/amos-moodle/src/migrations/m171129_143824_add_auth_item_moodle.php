<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m171129_143824_add_auth_item_moodle
 */
class m171129_143824_add_auth_item_moodle extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' => \open20\amos\moodle\widgets\icons\WidgetIconMoodle::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconMoodle',
                'ruleName' => null,
                'parent' => ['ADMIN', 'MOODLE_ADMIN', 'MOODLE_STUDENT']
            ]
        ];
    }
}
