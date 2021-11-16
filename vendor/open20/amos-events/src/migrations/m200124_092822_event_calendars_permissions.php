<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200124_092822_event_calendars_permissions*/
class m200124_092822_event_calendars_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'EVENTCALENDARS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model EventCalendars',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR','open20\amos\events\rules\EventsUpdateRule']
            ],
            [
                'name' => 'EVENTCALENDARS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model EventCalendars',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR','open20\amos\events\rules\EventsUpdateRule']
            ],
            [
                'name' => 'EVENTCALENDARS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model EventCalendars',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR','open20\amos\events\rules\EventsUpdateRule']
            ],
            [
                'name' => 'EVENTCALENDARS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model EventCalendars',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR','open20\amos\events\rules\EventsUpdateRule']
            ],

//------------------------------------------------
            [
                'name' => 'EVENTCALENDARSSLOTS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model EventCalendarsSlots',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'EVENTCALENDARSSLOTS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model EventCalendarsSlots',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR','open20\amos\events\rules\EventsUpdateRule']
            ],
            [
                'name' => 'EVENTCALENDARSSLOTS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model EventCalendarsSlots',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR','open20\amos\events\rules\EventsUpdateRule']
            ],
            [
                'name' => 'EVENTCALENDARSSLOTS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model EventCalendarsSlots',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR','open20\amos\events\rules\EventsUpdateRule']
            ],

        ];
    }
}
