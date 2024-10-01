<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;
use yii\db\Schema;

/**
 * Class m181022_100700_create_ticket
 */
class m181022_100700_create_ticket extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%ticket}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'titolo' => Schema::TYPE_STRING . "(255) DEFAULT NULL COMMENT 'Titolo'",
            'descrizione_breve' => Schema::TYPE_STRING . "(255) DEFAULT NULL COMMENT 'Descrizione breve'",
            'descrizione' => Schema::TYPE_TEXT . " COMMENT 'Descrizione'",
            'status' => Schema::TYPE_STRING . "(255) DEFAULT NULL COMMENT 'Stato'",
            'closed_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Chiuso da'",
            'closed_at' => Schema::TYPE_DATETIME . " DEFAULT NULL COMMENT 'Data chiusura del ticket'",
            'ticket_categoria_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Categoria del ticket'",
            'version' => $this->integer()->null()->defaultValue(null)->comment('Versione numero'),
            'forwarded_from_id' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Ticket precedente rispetto all\'inoltro'",
            'forwarded_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Inoltrato da'",
            'forwarded_at' => Schema::TYPE_DATETIME . " DEFAULT NULL COMMENT 'Data inoltro del ticket'",
            'forward_message' => Schema::TYPE_TEXT . " COMMENT 'Commento dell\'inoltro'",
            'forward_message_to_operator' => Schema::TYPE_BOOLEAN . " COMMENT 'Il commento dell\'inoltro lo vede anche l\'operatore'",
            'forward_notify' => Schema::TYPE_BOOLEAN . " COMMENT 'Inoltro notificato per email'",
            'partnership_id' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Ticket relativo alla partnership'", // open20\amos\organizzazioni\models\Profilo
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeTableCreation()
    {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }

    /**
     * @inheritdoc
     */
    protected function afterTableCreation()
    {
        $this->addCommentOnTable($this->tableName, 'ticket per assistenza');
    }

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_ticket_categoria_3', $this->tableName, 'ticket_categoria_id', 'ticket_categorie', 'id');
        //$this->addForeignKey('fk_ticket_forwarded_1', $this->tableName, 'forwarded_from_id', 'ticket', 'id');
    }
}
