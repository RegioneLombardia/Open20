<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

class m230414_163500_create_table_prodotti_destinatari extends AmosMigrationTableCreation {

    protected function setTableName() {
        $this->tableName = '{{%prodotti_destinatari}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'parent' => $this->integer()->notNull(),
            'icon' => $this->string()->notNull()
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
}
