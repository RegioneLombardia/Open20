<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */
use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m200805_115519_create_preference_campain_container_type
 */
class m200805_115519_create_preference_campain_container_type extends AmosMigrationTableCreation {

    /**
     * @inheritdoc
     */
    protected function setTableName() {
        $this->tableName = '{{%preference_campain_container_type}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->null()->defaultValue(null)->comment('Name'),
            'description' => $this->string(255)->null()->defaultValue(null)->comment('Description'), 
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
