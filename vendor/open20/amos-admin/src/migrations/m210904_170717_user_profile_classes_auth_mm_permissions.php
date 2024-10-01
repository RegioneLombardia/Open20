<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m210904_170717_user_profile_classes_auth_mm_permissions */
class m210904_170717_user_profile_classes_auth_mm_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'USERPROFILECLASSESAUTHMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model UserProfileClassesAuthMm',
                'ruleName' => null,
                'parent' => ['ADMIN', 'GESTIONE_UTENTI'],
            ],
            [
                'name' => 'USERPROFILECLASSESAUTHMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model UserProfileClassesAuthMm',
                'ruleName' => null,
                'parent' => ['ADMIN', 'GESTIONE_UTENTI'],
            ],
            [
                'name' => 'USERPROFILECLASSESAUTHMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model UserProfileClassesAuthMm',
                'ruleName' => null,
                'parent' => ['ADMIN', 'GESTIONE_UTENTI'],
            ],
            [
                'name' => 'USERPROFILECLASSESAUTHMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model UserProfileClassesAuthMm',
                'ruleName' => null,
                'parent' => ['ADMIN', 'GESTIONE_UTENTI'],
            ],
        ];
    }
}