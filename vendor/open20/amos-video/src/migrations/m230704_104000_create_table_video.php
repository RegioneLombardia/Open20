<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

class m230704_104000_create_table_video extends AmosMigrationTableCreation {

    protected function setTableName() {
        $this->tableName = '{{%video}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'in_evidenza' => $this->boolean(),
            'community_id' => $this->integer()->comment('ID Community')
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

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_video_community', $this->tableName, 'community_id', 'community', 'id');
    }
}
