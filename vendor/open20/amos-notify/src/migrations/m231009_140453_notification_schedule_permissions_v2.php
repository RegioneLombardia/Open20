<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m221128_160453_notification_schedule_permissions*/
class m231009_140453_notification_schedule_permissions_v2 extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'NOTIFY_SCHEDULE_ADMINISTRATOR',
                    'type' => Permission::TYPE_ROLE,
                    'description' => 'Permesso di CREATE sul model NotificationSchedule',
                    'ruleName' => null,
                    'parent' => ['ADMIN'],
                    'children' => [
                        'NOTIFICATIONSCHEDULE_READ',
                        'NOTIFICATIONSCHEDULE_UPDATE',
                        'NOTIFICATIONSCHEDULE_DELETE',
                        'NOTIFICATIONSCHEDULECONTENT_CREATE',
                        'NOTIFICATIONSCHEDULECONTENT_READ',
                        'NOTIFICATIONSCHEDULECONTENT_DELETE',
                    ]
                ],

            ];
    }
}
