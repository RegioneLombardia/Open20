<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;
use open20\amos\community\rules\UpdateBookmarksRule;


/**
 * Class m220728_155500_add_extra_community_links_permissions*/
class m220911_174800_add_extra_bookmarks_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => UpdateBookmarksRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Bookmarks',
                'ruleName' => UpdateBookmarksRule::className(),
                'parent' => ['BOOKMARKS_READER']
            ],
        ];
    }
}
