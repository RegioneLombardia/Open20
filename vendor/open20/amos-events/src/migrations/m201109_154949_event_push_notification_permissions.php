<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201109_154949_event_push_notification_permissions*/
class m201109_154949_event_push_notification_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'EVENTPUSHNOTIFICATION_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model EventPushNotification',
                    'ruleName' => null,
                    'parent' => ['EVENT_DG_OPERATOR', 'EVENTS_ADMINISTRATOR']
                ],
                [
                    'name' =>  'EVENTPUSHNOTIFICATION_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model EventPushNotification',
                    'ruleName' => null,
                    'parent' => ['EVENT_DG_OPERATOR', 'EVENTS_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'EVENTPUSHNOTIFICATION_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model EventPushNotification',
                    'ruleName' => null,
                    'parent' => ['EVENT_DG_OPERATOR', 'EVENTS_ADMINISTRATOR']
                ],
                [
                    'name' =>  'EVENTPUSHNOTIFICATION_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model EventPushNotification',
                    'ruleName' => null,
                    'parent' => ['EVENT_DG_OPERATOR', 'EVENTS_ADMINISTRATOR']
                ],
            //
            [
                'name' =>  'EVENTPUSHNOTIFICATIONSENT_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model EventPushNotificationSent',
                'ruleName' => null,
                'parent' => ['EVENT_DG_OPERATOR', 'EVENTS_ADMINISTRATOR']
            ],
            [
                'name' =>  'EVENTPUSHNOTIFICATIONSENT_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model EventPushNotificationSent',
                'ruleName' => null,
                'parent' => ['EVENT_DG_OPERATOR', 'EVENTS_ADMINISTRATOR']
            ],
            [
                'name' =>  'EVENTPUSHNOTIFICATIONSENT_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model EventPushNotificationSent',
                'ruleName' => null,
                'parent' => ['EVENT_DG_OPERATOR', 'EVENTS_ADMINISTRATOR']
            ],
            [
                'name' =>  'EVENTPUSHNOTIFICATIONSENT_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model EventPushNotificationSent',
                'ruleName' => null,
                'parent' => ['EVENT_DG_OPERATOR', 'EVENTS_ADMINISTRATOR']
            ],

            ];
    }
}
