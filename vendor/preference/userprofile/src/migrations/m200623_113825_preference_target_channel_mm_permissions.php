<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200623_113825_preference_target_channel_mm_permissions*/
class m200623_113825_preference_target_channel_mm_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'PREFERENCETARGETCHANNELMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model PreferenceTargetChannelMm',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],
            [
                'name' => 'PREFERENCETARGETCHANNELMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model PreferenceTargetChannelMm',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],
            [
                'name' => 'PREFERENCETARGETCHANNELMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model PreferenceTargetChannelMm',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],
            [
                'name' => 'PREFERENCETARGETCHANNELMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model PreferenceTargetChannelMm',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],

        ];
    }
}
