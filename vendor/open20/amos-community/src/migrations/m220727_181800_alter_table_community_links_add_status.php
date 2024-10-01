<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */


class m220727_181800_alter_table_community_links_add_status extends \yii\db\Migration
{
    const TABLE_NAME = 'community_links';
    const COLUMN = 'status';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, self::COLUMN, $this->integer(11)->notNull()->after('link')->comment('stato del link'));
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, self::COLUMN);
        return true;
    }
}
