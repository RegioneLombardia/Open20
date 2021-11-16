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
 * Class m201120_124600_insert_value_content_type
 */
class m201120_124600_insert_value_content_type extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // remove old value
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        \Yii::$app->db->createCommand()->truncateTable('news_content_type')->execute();
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
        
        $this->insert('news_content_type', [
            'name' => 'News'
        ]);
        
        $this->insert('news_content_type', [
            'name' => 'Avvisi'
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m201120_124600_insert_value_content_type cannot be reverted.\n";
        return false;
    }
}
