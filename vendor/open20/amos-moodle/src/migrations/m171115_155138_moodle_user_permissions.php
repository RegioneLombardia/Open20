<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m171115_155138_moodle_user_permissions
 */
class m171115_155138_moodle_user_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'MOODLEUSER_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model MoodleUser',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'MOODLEUSER_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model MoodleUser',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'MOODLEUSER_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model MoodleUser',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'MOODLEUSER_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model MoodleUser',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],

        ];
    }
}
