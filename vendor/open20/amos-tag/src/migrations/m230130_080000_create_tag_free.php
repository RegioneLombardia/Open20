<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Handles the creation of table `tag_free`.
 */
class m230130_080000_create_tag_free extends AmosMigrationTableCreation {

    protected function setTableName() {
        $this->tableName = '{{%tag_free}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'tag' => $this->string()->notNull(),
            'group_id' => $this->integer()->null(),
            'url' => $this->text(),
            'counter' => $this->integer()->defaultValue(0),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeTableCreation() {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }

}
