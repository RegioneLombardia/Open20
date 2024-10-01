<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Handles the creation of table `tag_free_model_mm`.
 */
class m230131_090000_create_tag_model_free extends AmosMigrationTableCreation {

    protected function setTableName() {
        $this->tableName = '{{%tag_free_model_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'tag_id' => $this->integer()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'content_id' => $this->integer()->notNull(),
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
