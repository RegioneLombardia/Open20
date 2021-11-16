<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m180412_155138_moodle_user_permissions_delete
 */
class m180412_155138_moodle_user_permissions_delete extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setProcessInverted(true);
    }

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

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
