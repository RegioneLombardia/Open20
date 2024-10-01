<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m210707_200620_sondaggi_comunication_user_mm_permissions*/
class m210707_200620_sondaggi_comunication_user_mm_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'SONDAGGICOMUNICATIONUSERMM_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model SondaggiComunicationUserMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'SONDAGGICOMUNICATIONUSERMM_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model SondaggiComunicationUserMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                    ],
                [
                    'name' =>  'SONDAGGICOMUNICATIONUSERMM_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model SondaggiComunicationUserMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'SONDAGGICOMUNICATIONUSERMM_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model SondaggiComunicationUserMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],

            ];
    }
}
