<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\community\rules\UserIsNotCommunityReaderBookmarksRule;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230123_163500_fix_bookmarks_reader_permissions_reader_community_role
 */
class m230123_163500_fix_bookmarks_reader_permissions_reader_community_role extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => UserIsNotCommunityReaderBookmarksRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola che controlla se un utente non ha il ruolo READER',
                'ruleName' => UserIsNotCommunityReaderBookmarksRule::className(),
                'parent' => [
                    'BOOKMARKS_READER'
                ]
            ],
            [
                'name' => 'BOOKMARKS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Bookmarks',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        UserIsNotCommunityReaderBookmarksRule::className()
                    ],
                    'removeParents' => [
                        'BOOKMARKS_READER'
                    ]
                ]
            ],
            [
                'name' => 'BOOKMARKS_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di vedere i community link',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'VALIDATED_BASIC_USER'
                    ],
                    'removeParents' => [
                        'BASIC_USER'
                    ]
                ]
            ]
        ];
    }
}
