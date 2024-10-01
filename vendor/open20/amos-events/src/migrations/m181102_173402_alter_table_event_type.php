<?php

use open20\amos\events\models\EventType;
use yii\db\Migration;

/**
 * Class m181102_193402_alter_table_event_type
 */
class m181102_173402_alter_table_event_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(EventType::tableName(), 'event_context_id', $this->integer()->null()->defaultValue(null));
        $this->addColumn(EventType::tableName(), 'event_type', $this->integer(1)->null()->comment('Tipo di evento'));
        $this->addColumn(EventType::tableName(), 'limited_seats', $this->boolean()->notNull()->defaultValue(0)->comment('Posti limitati?'));
        $this->addColumn(EventType::tableName(), 'manage_subscritions_queue', $this->boolean()->notNull()->defaultValue(0)->comment('Gestione della coda di iscrizioni?'));
        $this->addColumn(EventType::tableName(), 'partners', $this->boolean()->notNull()->defaultValue(0)->comment('Iscrizione di accomapgnatori?'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(EventType::tableName(), 'event_type');
        $this->dropColumn(EventType::tableName(), 'limited_seats');
        $this->dropColumn(EventType::tableName(), 'manage_subscritions_queue');
        $this->dropColumn(EventType::tableName(), 'partners');
        $this->alterColumn(EventType::tableName(), 'event_context_id', $this->integer()->notNull());
    }

}
