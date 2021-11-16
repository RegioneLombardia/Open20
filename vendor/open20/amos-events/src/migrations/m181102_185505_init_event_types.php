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
 * Class m181102_185505_init_event_types
 */
class m181102_185505_init_event_types extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert(EventType::tableName(), [
            'id' => 100,
            'title' => 'Evento informativo',
            'description' => 'Evento informativo',
            'color' => '#ffffff',
            'event_type' => EventType::TYPE_INFORMATIVE,
        ]);
        $this->insert(EventType::tableName(), [
            'id' => 101,
            'title' => 'Evento aperto',
            'description' => 'Evento aperto',
            'color' => '#ffffff',
            'event_type' => EventType::TYPE_OPEN,
            'partners' => 1,
        ]);
        $this->insert(EventType::tableName(), [
            'id' => 102,
            'title' => 'Evento su invito',
            'description' => 'Evento su invito',
            'color' => '#ffffff',
            'event_type' => EventType::TYPE_UPON_INVITATION,
            'partners' => 1,
        ]);
        $this->insert(EventType::tableName(), [
            'id' => 103,
            'title' => 'Evento a posti limitati',
            'description' => 'Evento a posti limitati',
            'color' => '#ffffff',
            'event_type' => EventType::TYPE_UPON_INVITATION,
            'limited_seats' => 1,
            'manage_subscritions_queue' => 1,
            'partners' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(EventType::tableName(), 'id IN (100, 101, 102, 103)');
    }
}
