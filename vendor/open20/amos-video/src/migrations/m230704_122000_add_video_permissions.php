<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;
use open20\video\rules\UserManagementVideoRule;

class m230704_122000_add_video_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => UserManagementVideoRule::class,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola che controlla se un utente può gestire il video',
                'ruleName' => UserManagementVideoRule::class,
                'parent' => [
                    'VIDEO_READER'
                ]
            ],
            [
                'name' => 'VIDEO_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Video',
                'ruleName' => null,
                'parent' => [
                    'VIDEO_ADMIN',
                    UserManagementVideoRule::class
                ]
            ],
            [
                'name' => 'VIDEO_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model Video',
                'ruleName' => null,
                'parent' => [
                    'VIDEO_ADMIN',
                    'VIDEO_READER'
                ]
            ],
            [
                'name' => 'VIDEO_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Video',
                'ruleName' => null,
                'parent' => [
                    'VIDEO_ADMIN',
                    UserManagementVideoRule::class
                ]
            ],
            [
                'name' => 'VIDEO_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model Video',
                'ruleName' => null,
                'parent' => [
                    'VIDEO_ADMIN',
                    UserManagementVideoRule::class
                ]
            ],

        ];
    }
}
