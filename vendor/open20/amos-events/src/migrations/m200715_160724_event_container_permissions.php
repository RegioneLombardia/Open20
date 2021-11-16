<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m200420_161524_event_container_permissions*/
class m200715_160724_event_container_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'EVENT_SOCIAL',
                    'type' => Permission::TYPE_ROLE,
                    'description' => 'Ruolo Social',
                    'ruleName' => null,
                ],

            ];
    }
}
