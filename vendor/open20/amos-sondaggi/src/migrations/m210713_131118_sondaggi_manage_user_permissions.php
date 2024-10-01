<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m210713_131118_sondaggi_manage_user_permissions*/
class m210713_131118_sondaggi_manage_user_permissions extends AmosMigrationPermissions
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
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AMMINISTRAZIONE_SONDAGGI']
                    ]
                ],
                [
                    'name' =>  'SONDAGGI_MANAGE',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AMMINISTRAZIONE_SONDAGGI']
                    ]
                ],

            ];
    }
}
