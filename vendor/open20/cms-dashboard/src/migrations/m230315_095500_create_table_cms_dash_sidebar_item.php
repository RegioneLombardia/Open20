<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

class m230315_095500_create_table_cms_dash_sidebar_item extends AmosMigrationTableCreation {

    protected function setTableName() {
        $this->tableName = '{{%cms_dash_sidebar_item}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'link' => $this->text()->notNull(),
            'link_shortcut' => $this->text()->null(),
            'label' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'shortcut_description' => $this->string()->null(),
            'icon_name' => $this->string(32)->notNull(),
            'id_container' => $this->string()->null(),
            'class_container' => $this->string()->null(),
            'isVisible' => $this->boolean()->notNull()->defaultValue(true),
            'isTargetBlank' => $this->boolean()->notNull()->defaultValue(false),
            'position' => $this->integer()->notNull()
        ];
    }

    protected function beforeTableCreation()
    {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }
}
