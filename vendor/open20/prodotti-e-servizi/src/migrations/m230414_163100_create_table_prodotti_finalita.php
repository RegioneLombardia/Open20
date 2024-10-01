<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

class m230414_163100_create_table_prodotti_finalita extends AmosMigrationTableCreation {

    protected function setTableName() {
        $this->tableName = '{{%prodotti_finalita}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
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
