<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200623_102900_preference_channel_permissions*/
class m200623_102900_preference_channel_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'PREFERENCECHANNEL_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model PreferenceChannel',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],
            [
                'name' => 'PREFERENCECHANNEL_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model PreferenceChannel',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],
            [
                'name' => 'PREFERENCECHANNEL_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model PreferenceChannel',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],
            [
                'name' => 'PREFERENCECHANNEL_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model PreferenceChannel',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],

        ];
    }
}
