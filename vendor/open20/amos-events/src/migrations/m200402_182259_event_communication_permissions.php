<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m200402_182259_event_communication_permissions*/
class m200402_182259_event_communication_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'EVENTCOMMUNICATION_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model EventCommunication',
                    'ruleName' => null,
                    'parent' => ['EVENTS_CREATOR','EVENTS_ADMINISTRATOR']
                ],
                [
                    'name' =>  'EVENTCOMMUNICATION_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model EventCommunication',
                    'ruleName' => null,
                    'parent' => ['EVENTS_CREATOR','EVENTS_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'EVENTCOMMUNICATION_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model EventCommunication',
                    'ruleName' => null,
                    'parent' => ['EVENTS_CREATOR','EVENTS_ADMINISTRATOR']
                ],
                [
                    'name' =>  'EVENTCOMMUNICATION_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model EventCommunication',
                    'ruleName' => null,
                    'parent' => ['EVENTS_CREATOR','EVENTS_ADMINISTRATOR']
                ],

            [
                'name' =>  'EVENTCOMMUNICATIONSENT_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model EventCommunicationSent',
                'ruleName' => null,
                'parent' => ['EVENTS_CREATOR','EVENTS_ADMINISTRATOR']
            ],
            [
                'name' =>  'EVENTCOMMUNICATIONSENT_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model EventCommunicationSent',
                'ruleName' => null,
                'parent' => ['EVENTS_CREATOR','EVENTS_ADMINISTRATOR']
            ],
            [
                'name' =>  'EVENTCOMMUNICATIONSENT_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model EventCommunicationSent',
                'ruleName' => null,
                'parent' => ['EVENTS_CREATOR','EVENTS_ADMINISTRATOR']
            ],
            [
                'name' =>  'EVENTCOMMUNICATIONSENT_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model EventCommunicationSent',
                'ruleName' => null,
                'parent' => ['EVENTS_CREATOR','EVENTS_ADMINISTRATOR']
            ],

            ];
    }
}
