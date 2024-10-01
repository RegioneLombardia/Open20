<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m191003_121523_event_seats_permissions*/
class m191003_121523_event_seats_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'EVENTSEATS_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model EventSeats',
                    'ruleName' => null,
                    'parent' => ['EVENTS_ADMINISTRATOR']
                ],
                [
                    'name' =>  'EVENTSEATS_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model EventSeats',
                    'ruleName' => null,
                    'parent' => ['EVENTS_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'EVENTSEATS_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model EventSeats',
                    'ruleName' => null,
                    'parent' => ['EVENTS_ADMINISTRATOR']
                ],
                [
                    'name' =>  'EVENTSEATS_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model EventSeats',
                    'ruleName' => null,
                    'parent' => ['EVENTS_ADMINISTRATOR']
                ],

            ];
    }
}
