<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m200615_175354_sondaggi_modelli_predefiniti_permissions*/
class m200615_175354_sondaggi_modelli_predefiniti_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'SONDAGGIMODELLIPREDEFINITI_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model SondaggiModelliPredefiniti',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'SONDAGGIMODELLIPREDEFINITI_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model SondaggiModelliPredefiniti',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                    ],
                [
                    'name' =>  'SONDAGGIMODELLIPREDEFINITI_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model SondaggiModelliPredefiniti',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'SONDAGGIMODELLIPREDEFINITI_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model SondaggiModelliPredefiniti',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],

            ];
    }
}
