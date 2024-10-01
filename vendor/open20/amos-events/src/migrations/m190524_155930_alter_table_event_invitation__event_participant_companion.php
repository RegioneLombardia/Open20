<?php

use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventParticipantCompanion;
use yii\db\Migration;

/**
 * Class m190524_155930_alter_table_event_invitation__event_participant_companion
 */
class m190524_155930_alter_table_event_invitation__event_participant_companion extends Migration
{
    private 
        $tableEventInvitationName,
        $tableEventParticipantCompanionName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableEventInvitationName = EventInvitation::tableName();
        $this->tableEventParticipantCompanionName = EventParticipantCompanion::tableName();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $table = \Yii::$app->db->schema->getTableSchema($this->tableEventInvitationName);
        if (!isset($table->columns['presenza_scansionata_il'])) {
            $this->addColumn($this->tableEventInvitationName, 'presenza_scansionata_il', $this->dateTime()->null()->comment('Presenza scansionata il')->after('presenza'));
        }
        
        $table = \Yii::$app->db->schema->getTableSchema($this->tableEventParticipantCompanionName);
        if (!isset($table->columns['presenza_scansionata_il'])) {
            $this->addColumn($this->tableEventParticipantCompanionName, 'presenza_scansionata_il', $this->dateTime()->null()->comment('Presenza scansionata il')->after('presenza'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(\open20\amos\events\models\EventInvitation::tableName(), 'presenza_scansionata_il');
        $this->dropColumn(\open20\amos\events\models\EventParticipantCompanion::tableName(), 'presenza_scansionata_il');
    }

}
