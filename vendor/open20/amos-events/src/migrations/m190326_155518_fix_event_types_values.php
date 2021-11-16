<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\events\models\EventType;
use yii\db\Migration;

/**
 * Class m190326_155518_fix_event_types_values
 */
class m190326_155518_fix_event_types_values extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update(EventType::tableName(), ['event_context_id' => 1], ['event_context_id' => null]);
        $this->update(EventType::tableName(), ['created_by' => 1], ['created_by' => null]);
        $this->update(EventType::tableName(), ['updated_by' => 1], ['updated_by' => null]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190326_155518_fix_event_types_values cannot be reverted.\n";
        return false;
    }
}
