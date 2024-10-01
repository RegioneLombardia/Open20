<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m210408_162634_event_configurations_permissions*/
class m210408_162634_event_configurations_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'CONFIGURATOR_EVENTS',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Configurator events',
                'ruleName' => null,
//                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'EVENTCONFIGURATIONS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model EventConfigurations',
                'ruleName' => null,
                'parent' => ['CONFIGURATOR_EVENTS']
            ],
            [
                'name' => 'EVENTCONFIGURATIONS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model EventConfigurations',
                'ruleName' => null,
                'parent' => ['CONFIGURATOR_EVENTS']
            ],
            [
                'name' => 'EVENTCONFIGURATIONS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model EventConfigurations',
                'ruleName' => null,
                'parent' => ['CONFIGURATOR_EVENTS']
            ],
//            [
//                'name' => 'EVENTCONFIGURATIONS_DELETE',
//                'type' => Permission::TYPE_PERMISSION,
//                'description' => 'Permesso di DELETE sul model EventConfigurations',
//                'ruleName' => null,
//                'parent' => ['CONFIGURATOR_EVENTS']
//            ],

        ];
    }
}
