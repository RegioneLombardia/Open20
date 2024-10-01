<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

class m221014_101700_alter_column_status_bookmarks extends \yii\db\Migration
{
    const TABLE_NAME = 'bookmarks';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn(self::TABLE_NAME, 'status', 'string not null');
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn(self::TABLE_NAME, 'status', 'integer');
        return true;
    }
}