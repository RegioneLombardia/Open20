<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200124_092822_event_calendars_permissions*/
class m200129_152622_event_calendars_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {

        return [
            [
                'name' => 'EVENTCALENDARS_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENTS_READER']
                ]
            ]
        ];
    }
}
