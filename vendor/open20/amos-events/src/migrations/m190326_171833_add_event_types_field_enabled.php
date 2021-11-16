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
 * Class m190326_171833_add_event_types_field_enabled
 */
class m190326_171833_add_event_types_field_enabled extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = EventType::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'enabled', $this->boolean()->notNull()->defaultValue(1)->after('logoRequested'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'enabled');
        return true;
    }
}
