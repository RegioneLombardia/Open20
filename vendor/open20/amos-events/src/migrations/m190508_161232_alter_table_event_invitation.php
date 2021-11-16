<?php

use open20\amos\events\models\EventInvitation;
use yii\db\Migration;

/**
 * Class m181106_150933_alter_table_event
 */
class m190508_161232_alter_table_event_invitation extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = EventInvitation::tableName();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $table = \Yii::$app->db->schema->getTableSchema($this->tableName);
        
        if (!isset($table->columns['accreditation_list_id'])) {
            $this->addColumn($this->tableName, 'accreditation_list_id', $this->integer()->null()->defaultValue(null)->comment('Id lista di accreditamento')->after('partner_of'));
        }
        
        if (!isset($table->columns['is_ticket_sent'])) {
            $this->addColumn($this->tableName, 'is_ticket_sent', $this->boolean()->notNull()->defaultValue(0)->comment('Biglietto inviato?')->after('accreditation_list_id'));
        }

        if (!isset($table->columns['ticket_downloaded_at'])) {
            $this->addColumn($this->tableName, 'ticket_downloaded_at', $this->dateTime()->null()->defaultValue(null)->comment('Biglietto scaricato dall\'utente alle')->after('is_ticket_sent'));
        }
        
        if (!isset($table->columns['ticket_downloaded_by'])) {
            $this->addColumn($this->tableName, 'ticket_downloaded_by', $this->integer()->null()->defaultValue(null)->comment('Biglietto scaricato dall\'utente')->after('ticket_downloaded_at'));
        }
        
        if (!isset($table->columns['presenza'])) {
            $this->addColumn($this->tableName, 'presenza', $this->boolean()->notNull()->defaultValue(0)->comment('Presente all\'evento?')->after('ticket_downloaded_by'));
        }
        
        if (!isset($table->columns['notes'])) {
            $this->addColumn($this->tableName, 'notes', $this->text()->null()->comment('Note')->after('presenza'));
        }

        if (!isset($table->columns['company'])) {
            $this->addColumn($this->tableName, 'company', $this->string(255)->null()->comment('Ragione sociale azienda')->after('notes'));
        }
        
        if (!isset($table->columns['gdpr_answer_1'])) {
            $this->addColumn($this->tableName, 'gdpr_answer_1', $this->boolean()->null()->defaultValue(null)->after('company'));
        }
        
        if (!isset($table->columns['gdpr_answer_2'])) {
            $this->addColumn($this->tableName, 'gdpr_answer_2', $this->boolean()->null()->defaultValue(null)->after('gdpr_answer_1'));
        }
        
        if (!isset($table->columns['gdpr_answer_3'])) {
            $this->addColumn($this->tableName, 'gdpr_answer_3', $this->boolean()->null()->defaultValue(null)->after('gdpr_answer_2'));
        }
        
        if (!isset($table->columns['gdpr_answer_4'])) {
            $this->addColumn($this->tableName, 'gdpr_answer_4', $this->boolean()->null()->defaultValue(null)->after('gdpr_answer_3'));
        }
        
        if (!isset($table->columns['gdpr_answer_5'])) {
            $this->addColumn($this->tableName, 'gdpr_answer_5', $this->boolean()->null()->defaultValue(null)->after('gdpr_answer_4'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(EventInvitation::tableName(), 'accreditation_list_id');
        $this->dropColumn(EventInvitation::tableName(), 'is_ticket_sent');
        $this->dropColumn(EventInvitation::tableName(), 'ticket_downloaded_at');
        $this->dropColumn(EventInvitation::tableName(), 'ticket_downloaded_by');
        $this->dropColumn(EventInvitation::tableName(), 'has_attended');
        $this->dropColumn(EventInvitation::tableName(), 'notes');
        $this->dropColumn(EventInvitation::tableName(), 'company');
        $this->dropColumn(EventInvitation::tableName(), 'gdpr_answer_1');
        $this->dropColumn(EventInvitation::tableName(), 'gdpr_answer_2');
        $this->dropColumn(EventInvitation::tableName(), 'gdpr_answer_3');
        $this->dropColumn(EventInvitation::tableName(), 'gdpr_answer_4');
        $this->dropColumn(EventInvitation::tableName(), 'gdpr_answer_5');
    }

}
