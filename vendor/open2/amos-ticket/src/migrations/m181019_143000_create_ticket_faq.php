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
 * Class m181019_143000_create_ticket_faq
 */
class m181019_143000_create_ticket_faq extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%ticket_faq}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'domanda' => $this->text()->null()->defaultValue(null)->comment('Domanda'),
            'risposta' => $this->text()->null()->defaultValue(null)->comment('Risposta'),
            'ticket_categoria_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Categoria della faq'",
            'version' => $this->integer()->null()->defaultValue(null)->comment('Versione numero')
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
        $this->addCommentOnTable($this->tableName, 'categorie per i ticket e le faq dei ticket');
    }

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_ticket_categoria', $this->tableName, 'ticket_categoria_id', 'ticket_categorie', 'id');
    }
}
