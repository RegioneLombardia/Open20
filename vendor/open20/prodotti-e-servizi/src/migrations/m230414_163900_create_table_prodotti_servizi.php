<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

class m230414_163900_create_table_prodotti_servizi extends AmosMigrationTableCreation {

    protected function setTableName() {
        $this->tableName = '{{%prodotti_servizi}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'id_product_root' => $this->integer()->null(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'financial_description' => $this->text()->notNull(),
            'facilitation_features' => $this->text()->notNull(),
            'projects_features' => $this->text()->notNull(),
            'expenses_eligible' => $this->text()->notNull(),
            'status' => $this->string()->notNull(),
            'scope' => $this->integer()->notNull(),
            'solution' => $this->integer()->notNull()
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
