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
 * Class m210115_154100_change_name_news_groups
 */
class m210115_154100_change_name_news_groups extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // remove old value
        $this->update('news_groups', ['name' => 'Gruppo 1'], ['id' => 1]);
        $this->update('news_groups', ['name' => 'Gruppo 2'], ['id' => 2]);
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m210115_154100_change_name_news_groups cannot be reverted.\n";
        return false;
    }
}
