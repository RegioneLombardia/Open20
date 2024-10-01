<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\community\rules\WidgetIconBookmarksRule;
use open20\amos\community\widgets\icons\WidgetIconBookmarks;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 *
 * Class m230731_093900_update_widget_icon_bookmarks_permissions
 */
class m230731_093900_update_widget_icon_bookmarks_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => WidgetIconBookmarksRule::class,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola che controlla se un utente può vedere il widget dei bookmarks',
                'ruleName' => WidgetIconBookmarksRule::class,
                'parent' => [
                    'BOOKMARKS_ADMIN',
                    'BOOKMARKS_READER'
                ]
            ],
            [
                'name' =>  WidgetIconBookmarks::class,
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'removeParents' => [
                        'ADMIN',
                        'BASIC_USER'
                    ],
                    'addParents' => [
                        WidgetIconBookmarksRule::class
                    ]
                ]
            ]
        ];
    }
}
