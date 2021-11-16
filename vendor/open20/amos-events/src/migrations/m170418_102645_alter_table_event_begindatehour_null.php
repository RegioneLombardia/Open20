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
 * Class m170418_102645_alter_table_event_begindatehour_null
 */
class m170418_102645_alter_table_event_begindatehour_null extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn(Event::tableName(), 'begin_date_hour', $this->dateTime()->null()->defaultValue(null));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn(Event::tableName(), 'begin_date_hour', $this->dateTime()->notNull());
    }
}
