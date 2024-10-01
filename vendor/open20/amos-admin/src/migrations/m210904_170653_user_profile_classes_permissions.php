<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m210904_170653_user_profile_classes_permissions */
class m210904_170653_user_profile_classes_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'USERPROFILECLASSES_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model UserProfileClasses',
                'ruleName' => null,
                'parent' => ['ADMIN', 'GESTIONE_UTENTI'],
            ],
            [
                'name' => 'USERPROFILECLASSES_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model UserProfileClasses',
                'ruleName' => null,
                'parent' => ['ADMIN', 'GESTIONE_UTENTI'],
            ],
            [
                'name' => 'USERPROFILECLASSES_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model UserProfileClasses',
                'ruleName' => null,
                'parent' => ['ADMIN', 'GESTIONE_UTENTI'],
            ],
            [
                'name' => 'USERPROFILECLASSES_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model UserProfileClasses',
                'ruleName' => null,
                'parent' => ['ADMIN', 'GESTIONE_UTENTI'],
            ],
        ];
    }
}