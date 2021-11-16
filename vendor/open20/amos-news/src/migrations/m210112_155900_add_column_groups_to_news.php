<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m210112_155900_add_column_groups_to_news
 */
class m210112_155900_add_column_groups_to_news extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        //  FK columns addForeignKey
        $this->addColumn('news', 'news_groups_id', $this->integer()->null()->defaultValue(null));
        $this->addForeignKey('fk_news_groups', 'news', 'news_groups_id', 'news_groups', 'id');
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if ($this->db->driverName === 'mysql') {
            $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
        }
        $this->dropForeignKey('fk_news_groups', 'news');
        // dropColumn
        $this->dropColumn('news', 'news_groups_id');
        if ($this->db->driverName === 'mysql') {
            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        }
    }
}
