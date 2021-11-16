<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m191022_160214_notification_language_preferences_permissions*/
class m191022_160214_notification_language_preferences_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'NOTIFICATIONLANGUAGEPREFERENCES_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model NotificationLanguagePreferences',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'NOTIFICATIONLANGUAGEPREFERENCES_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model NotificationLanguagePreferences',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                    ],
                [
                    'name' =>  'NOTIFICATIONLANGUAGEPREFERENCES_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model NotificationLanguagePreferences',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'NOTIFICATIONLANGUAGEPREFERENCES_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model NotificationLanguagePreferences',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],

            ];
    }
}
