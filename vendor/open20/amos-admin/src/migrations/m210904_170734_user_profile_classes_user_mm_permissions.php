<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m210904_170734_user_profile_classes_user_mm_permissions */
class m210904_170734_user_profile_classes_user_mm_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'USERPROFILECLASSESUSERMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model UserProfileClassesUserMm',
                'ruleName' => null,
                'parent' => ['ADMIN', 'GESTIONE_UTENTI'],
            ],
            [
                'name' => 'USERPROFILECLASSESUSERMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model UserProfileClassesUserMm',
                'ruleName' => null,
                'parent' => ['ADMIN', 'GESTIONE_UTENTI'],
            ],
            [
                'name' => 'USERPROFILECLASSESUSERMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model UserProfileClassesUserMm',
                'ruleName' => null,
                'parent' => ['ADMIN', 'GESTIONE_UTENTI'],
            ],
            [
                'name' => 'USERPROFILECLASSESUSERMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model UserProfileClassesUserMm',
                'ruleName' => null,
                'parent' => ['ADMIN', 'GESTIONE_UTENTI'],
            ],
        ];
    }
}