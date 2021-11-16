<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200623_113550_preference_sending_frequency_permissions*/
class m200623_113550_preference_sending_frequency_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'PREFERENCESENDINGFREQUENCY_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model PreferenceSendingFrequency',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],
            [
                'name' => 'PREFERENCESENDINGFREQUENCY_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model PreferenceSendingFrequency',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],
            [
                'name' => 'PREFERENCESENDINGFREQUENCY_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model PreferenceSendingFrequency',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],
            [
                'name' => 'PREFERENCESENDINGFREQUENCY_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model PreferenceSendingFrequency',
                'ruleName' => null,
                'parent' => ['ADMIN'],
            ],

        ];
    }
}
