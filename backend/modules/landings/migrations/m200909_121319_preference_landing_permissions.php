<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200909_121319_preference_landing_permissions*/
class m200909_121319_preference_landing_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [

            [
                'name' => 'PC_LANDING_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore landing',
                'ruleName' => null,
                'parent' => ['PC_ADMIN']
            ],
            [
                'name' => 'PREFERENCELANDING_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model PreferenceLanding',
                'ruleName' => null,
                'parent' => ['PC_LANDING_ADMINISTRATOR']
            ],
            [
                'name' => 'PREFERENCELANDING_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model PreferenceLanding',
                'ruleName' => null,
                'parent' => ['PC_LANDING_ADMINISTRATOR']
            ],
            [
                'name' => 'PREFERENCELANDING_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model PreferenceLanding',
                'ruleName' => null,
                'parent' => ['PC_LANDING_ADMINISTRATOR']
            ],
            [
                'name' => 'PREFERENCELANDING_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model PreferenceLanding',
                'ruleName' => null,
                'parent' => ['PC_LANDING_ADMINISTRATOR']
            ],

        ];
    }
}
