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
 * Class m210112_184600_insert_value_news_groups
 */
class m210112_184600_insert_value_news_groups extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // remove old value
        
        $this->insert('news_groups', [
            'name' => 'Timeline'
        ]);
        
        
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m210112_184600_insert_value_news_groups cannot be reverted.\n";
        return false;
    }
}
