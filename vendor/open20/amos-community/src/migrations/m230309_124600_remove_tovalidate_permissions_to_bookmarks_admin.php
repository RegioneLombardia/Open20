<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

class m230309_124600_remove_tovalidate_permissions_to_bookmarks_admin extends \yii\db\Migration
{
    const TABLE_NAME = 'auth_item_child';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->delete(self::TABLE_NAME, ['parent' => 'BOOKMARKS_ADMIN', 'child' => 'BookmarksWorkflow/TOVALIDATE']);
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->insert(self::TABLE_NAME, ['parent' => 'BOOKMARKS_ADMIN', 'child' => 'BookmarksWorkflow/TOVALIDATE']);
        return true;
    }
}