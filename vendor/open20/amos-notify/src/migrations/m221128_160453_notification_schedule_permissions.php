<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m221128_160453_notification_schedule_permissions*/
class m221128_160453_notification_schedule_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'NOTIFICATIONSCHEDULE_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model NotificationSchedule',
                    'ruleName' => null,
                    'parent' => ['NOTIFY_ADMINISTRATOR']
                ],
                [
                    'name' =>  'NOTIFICATIONSCHEDULE_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model NotificationSchedule',
                    'ruleName' => null,
                    'parent' => ['NOTIFY_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'NOTIFICATIONSCHEDULE_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model NotificationSchedule',
                    'ruleName' => null,
                    'parent' => ['NOTIFY_ADMINISTRATOR']
                ],
                [
                    'name' =>  'NOTIFICATIONSCHEDULE_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model NotificationSchedule',
                    'ruleName' => null,
                    'parent' => ['NOTIFY_ADMINISTRATOR']
                ],
            [
                'name' =>  'NOTIFICATIONSCHEDULECONTENT_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NotificationScheduleContent',
                'ruleName' => null,
                'parent' => ['NOTIFY_ADMINISTRATOR']
            ],
            [
                'name' =>  'NOTIFICATIONSCHEDULECONTENT_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NotificationScheduleContent',
                'ruleName' => null,
                'parent' => ['NOTIFY_ADMINISTRATOR']
            ],
            [
                'name' =>  'NOTIFICATIONSCHEDULECONTENT_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NotificationScheduleContent',
                'ruleName' => null,
                'parent' => ['NOTIFY_ADMINISTRATOR']
            ],
            [
                'name' =>  'NOTIFICATIONSCHEDULECONTENT_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NotificationScheduleContent',
                'ruleName' => null,
                'parent' => ['NOTIFY_ADMINISTRATOR']
            ],

            ];
    }
}
