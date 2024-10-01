<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Handles the creation of table `cms_page_profiles`.
 */
class m230707_092700_create_table_pages_profiles extends AmosMigrationTableCreation {

    protected function setTableName() {
        $this->tableName = '{{%cms_page_profiles}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'nav_id' => $this->integer()->notNull(),
            'user_profile_classes_id' => $this->integer()->notNull(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeTableCreation() {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }

    protected function addForeignKeys() {
        $this->addForeignKey('fk_cms_nav_id0', $this->tableName, 'nav_id', '{{%cms_nav}}', 'id');
        $this->addForeignKey('fk_userprofclasses_id0', $this->tableName, 'user_profile_classes_id', '{{%user_profile_classes}}', 'id');
    }
}
