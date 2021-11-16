<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m171128_154600_add_moodle_all_permissions
 */
class m171128_154600_add_moodle_all_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'MOODLE_ADMIN',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Moodle plugin administrator',
                'ruleName' => null,
            ],
            [
                'name' => 'MOODLE_STUDENT',
                'type' => Permission::TYPE_ROLE,
                'description' => 'A student in Moodle Plugin',
                'ruleName' => null,
            ],
            [
                'name' => 'COMMUNITY_CREATOR',
                'update' => true,
                'newValues' => ['addParents' => [
                    'MOODLE_ADMIN'
                ]]
            ],
            [
                'name' => 'CommunityWorkflow/VALIDATED',
                'update' => true,
                'newValues' => ['addParents' => [
                    'MOODLE_ADMIN'
                ]]
            ]
        ];
    }
}
