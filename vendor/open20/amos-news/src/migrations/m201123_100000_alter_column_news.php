<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\news\models\News;
use yii\db\Migration;

/**
 * Class m201123_100000_alter_column_news
 */
class m201123_100000_alter_column_news extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn(News::tableName(), "titolo", $this->text()->null()->defaultValue(null));
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m201123_100000_alter_column_news cannot be reverted.\n";
        return false;
    }
}
