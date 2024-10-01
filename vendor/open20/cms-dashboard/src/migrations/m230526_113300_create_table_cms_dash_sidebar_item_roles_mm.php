<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

class m230526_113300_create_table_cms_dash_sidebar_item_roles_mm extends AmosMigrationTableCreation {

    protected function setTableName() {
        $this->tableName = '{{%cms_dash_sidebar_item_roles_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'id_item' => $this->integer()->notNull(),
            'role' => $this->string()->notNull(),
        ];
    }

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
        $this->addForeignKey('fk_cms_dash_sidebar_item_roles_mm', $this->tableName, 'id_item', 'cms_dash_sidebar_item', 'id');
    }
}
