<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

class m220911_124300_alter_table_community_links_name extends \yii\db\Migration
{
    const TABLE_NAME = 'community_links';
    const NEW_TABLE_NAME = 'bookmarks';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameTable(self::TABLE_NAME, self::NEW_TABLE_NAME);
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameTable(self::NEW_TABLE_NAME, self::TABLE_NAME);
        return true;
    }
}