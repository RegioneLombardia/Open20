<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Handles the creation of table `log_cookie`.
 */
class m230112_120004_create_table_wf extends AmosMigrationTableCreation {

    protected function setTableName() {
        $this->tableName = '{{%cms_wf_request}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'title' => $this->string()->null(),
            'description' => $this->text()->null(),
            'message' => $this->text()->null(),
            'nav_id' => $this->integer()->notNull(),
            'nav_item_page_id' => $this->integer()->notNull(),
            'nav_item_id' => $this->integer()->notNull(),
            'from_user' => $this->integer()->notNull(),
            'processed' => $this->integer()->defaultValue(0),
            'processed_by_user' => $this->integer()->null(),
            'hash' => $this->string()->null(),
            'url' => $this->text()->null(),
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
