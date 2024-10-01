<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m200421_085955_add_event_column_event_room_id
 */
class m200421_085955_add_event_column_event_room_id extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        /** @var Event $eventModel */
        $eventModel = AmosEvents::instance()->createModel('Event');
        $this->tableName = $eventModel::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'event_room_id', $this->integer()->null()->defaultValue(null)->after('email_ticket_subject'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'event_room_id');
        return true;
    }
}
