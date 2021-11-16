<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m181008_174752_token_group_permissions*/
class m181008_174752_token_group_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'ADMINISTRATOR_TOKEN',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model TokenGroup',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'TOKENGROUP_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model TokenGroup',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_TOKEN']
            ],
            [
                'name' =>  'TOKENGROUP_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model TokenGroup',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_TOKEN']
            ],
            [
                'name' =>  'TOKENGROUP_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model TokenGroup',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_TOKEN']
            ],
            [
                'name' =>  'TOKENGROUP_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model TokenGroup',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_TOKEN']
            ],
//---------------------------------------------
            [
                'name' =>  'TOKENUSERS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model TokenUsers',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_TOKEN']
            ],
            [
                'name' =>  'TOKENUSERS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model TokenUsers',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_TOKEN']
            ],
            [
                'name' =>  'TOKENUSERS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model TokenUsers',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_TOKEN']
            ],
            [
                'name' =>  'TOKENUSERS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model TokenUsers',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_TOKEN']
            ],

        ];
    }
}
