<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230403_115835_socialwall_permissions*/
class m230403_115835_socialwall_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'SOCIALWALL_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Socialwall',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],
            [
                'name' =>  'SOCIALWALL_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model Socialwall',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],
            [
                'name' =>  'SOCIALWALL_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Socialwall',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],
            [
                'name' =>  'SOCIALWALL_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model Socialwall',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],

        ];
    }
}
