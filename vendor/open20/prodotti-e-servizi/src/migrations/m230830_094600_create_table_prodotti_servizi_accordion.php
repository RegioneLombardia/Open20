<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

class m230830_094600_create_table_prodotti_servizi_accordion extends AmosMigrationTableCreation {

    protected function setTableName() {
        $this->tableName = '{{%prodotti_servizi_accordion}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'is_visible' => $this->boolean(),
            'position' => $this->integer(),
            'prodotto_id' => $this->integer()
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
        $this->addForeignKey('fk_prodotti_servizi', $this->tableName, 'prodotto_id', 'prodotti_servizi', 'id');
    }
}
