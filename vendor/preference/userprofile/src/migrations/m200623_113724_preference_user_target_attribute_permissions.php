<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200623_113724_preference_user_target_attribute_permissions*/
class m200623_113724_preference_user_target_attribute_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'PREFERENCEUSERTARGETATTRIBUTE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model PreferenceUserTargetAttribute',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],
            [
                'name' => 'PREFERENCEUSERTARGETATTRIBUTE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model PreferenceUserTargetAttribute',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],
            [
                'name' => 'PREFERENCEUSERTARGETATTRIBUTE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model PreferenceUserTargetAttribute',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],
            [
                'name' => 'PREFERENCEUSERTARGETATTRIBUTE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model PreferenceUserTargetAttribute',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],

        ];
    }
}
