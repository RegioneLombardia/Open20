<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m210712_165940_sondaggi_manage_user_permissions*/
class m210712_165940_sondaggi_manage_user_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'DASHBOARD_VIEW',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso per accedere a dashboard',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'SONDAGGI_MANAGE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso per accedere a sondaggi/manage',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],

            ];
    }
}
