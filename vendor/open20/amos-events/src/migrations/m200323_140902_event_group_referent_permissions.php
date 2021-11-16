<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200323_140902_event_group_referent_permissions
 */
class m200323_140902_event_group_referent_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'EVENTGROUPREFERENT_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model EventGroupReferent',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'EVENTGROUPREFERENT_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model EventGroupReferent',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'EVENTGROUPREFERENT_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model EventGroupReferent',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'EVENTGROUPREFERENT_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model EventGroupReferent',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}