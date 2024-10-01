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

/**
 * Class m181019_150700_create_table_ticket_categorie_users_mm
 */
class m181019_150700_create_table_ticket_categorie_users_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%ticket_categorie_users_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'ticket_categoria_id' => $this->integer()->notNull(),
            'user_profile_id' => $this->integer()->notNull()
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
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_ticket_categoria_users_mm', $this->getRawTableName(), 'ticket_categoria_id', '{{%ticket_categorie}}', 'id');
        $this->addForeignKey('fk_users_ticket_categoria_mm', $this->getRawTableName(), 'user_profile_id', '{{%user_profile}}', 'id');
    }
}
