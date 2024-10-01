<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;
use open20\amos\community\rules\workflow\bookmarks\PublishedBookmarkReaderRule;
use open20\amos\community\rules\workflow\bookmarks\ToValidateBookmarkReaderRule;
use open20\amos\community\rules\workflow\bookmarks\DraftBookmarkReaderRule;

/**
 * Class m220728_155500_add_extra_community_links_permissions*/
class m221103_121900_add_bookmarks_workflow_rules extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => PublishedBookmarkReaderRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di PUBLISHED sul workflow Bookmarks',
                'ruleName' => PublishedBookmarkReaderRule::className(),
                'parent' => ['BOOKMARKS_READER']
            ],
            [
                'name' => ToValidateBookmarkReaderRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di TOVALIDATE sul workflow Bookmarks',
                'ruleName' => ToValidateBookmarkReaderRule::className(),
                'parent' => ['BOOKMARKS_READER']
            ],
            [
                'name' => DraftBookmarkReaderRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DRAFT sul workflow Bookmarks',
                'ruleName' => DraftBookmarkReaderRule::className(),
                'parent' => ['BOOKMARKS_READER']
            ],
        ];
    }
}
