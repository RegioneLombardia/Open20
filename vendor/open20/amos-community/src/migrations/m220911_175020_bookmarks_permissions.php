<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;
use open20\amos\community\rules\UpdateBookmarksRule;

class m220911_175020_bookmarks_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'BOOKMARKS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Bookmarks',
                'ruleName' => null,
                'parent' => [
                    'BOOKMARKS_ADMIN',
                    'BOOKMARKS_READER'
                ]
            ],
            [
                'name' => 'BOOKMARKS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model Bookmarks',
                'ruleName' => null,
                'parent' => [
                    'BOOKMARKS_ADMIN',
                    'BOOKMARKS_READER'
                ]
            ],
            [
                'name' => 'BOOKMARKS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Bookmarks',
                'ruleName' => null,
                'parent' => [
                    'BOOKMARKS_ADMIN',
                    UpdateBookmarksRule::className()
                ]
            ],
            [
                'name' => 'BOOKMARKS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model Bookmarks',
                'ruleName' => null,
                'parent' => [
                    'BOOKMARKS_ADMIN',
                    UpdateBookmarksRule::className()
                ]
            ],

        ];
    }
}
