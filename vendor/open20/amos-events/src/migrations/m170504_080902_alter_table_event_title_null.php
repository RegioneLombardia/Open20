<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m170504_080902_alter_table_event_title_null
 */
class m170504_080902_alter_table_event_title_null extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn(Event::tableName(), 'title', $this->string(255)->null()->defaultValue(null));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn(Event::tableName(), 'title', $this->string(255)->notNull());
    }
}
