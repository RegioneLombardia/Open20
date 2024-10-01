<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m230123_175539_favorite_permissions*/
class m230123_175539_favorite_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'FAVORITE_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Favorite administrator',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'FAVORITE_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Favorite reader',
                'ruleName' => null,
                'parent' => ['BASIC_USER']
            ],
                [
                    'name' =>  'FAVORITE_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model Favorite',
                    'ruleName' => null,
                    'parent' => ['FAVORITE_ADMINISTRATOR','FAVORITE_READER']
                ],
                [
                    'name' =>  'FAVORITE_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model Favorite',
                    'ruleName' => null,
                    'parent' => ['FAVORITE_ADMINISTRATOR','FAVORITE_READER']
                    ],
                [
                    'name' =>  'FAVORITE_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model Favorite',
                    'ruleName' => null,
                    'parent' => ['FAVORITE_ADMINISTRATOR']
                ],
                [
                    'name' =>  'FAVORITE_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model Favorite',
                    'ruleName' => null,
                    'parent' => ['FAVORITE_ADMINISTRATOR']
                ],

            ];
    }
}
