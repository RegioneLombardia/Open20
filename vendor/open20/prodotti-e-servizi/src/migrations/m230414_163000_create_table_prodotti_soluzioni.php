<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

class m230414_163000_create_table_prodotti_soluzioni extends AmosMigrationTableCreation {

    protected function setTableName() {
        $this->tableName = '{{%prodotti_soluzioni}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
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
