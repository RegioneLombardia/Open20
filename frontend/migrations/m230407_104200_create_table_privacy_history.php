<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

class m230407_104200_create_table_privacy_history extends AmosMigrationTableCreation {

    protected function setTableName() {
        $this->tableName = '{{%privacy_history}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer(11)->notNull(),
            'timestamp' => $this->integer(11)->notnull(),
            'isAccepted' => $this->boolean()->notNull()
        ];
    }
}
