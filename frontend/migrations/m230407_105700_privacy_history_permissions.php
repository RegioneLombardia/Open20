<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230407_105700_privacy_history_permissions*/
class m230407_105700_privacy_history_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'PRIVACYHISTORY_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model PrivacyHistory',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PRIVACYHISTORY_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model PrivacyHistory',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PRIVACYHISTORY_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model PrivacyHistory',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PRIVACYHISTORY_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model PrivacyHistory',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],

        ];
    }
}
