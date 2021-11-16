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
 * Class m200826_160023_create_preference_structure
 */
class m200826_160023_create_preference_structure extends AmosMigrationTableCreation {

    /**
     * @inheritdoc
     */
    protected function setTableName() {
        $this->tableName = '{{%preference_structure}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->comment('Nome'),
            'description' => $this->string(255)->null()->defaultValue(null)->comment('Descrizione'),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeTableCreation() {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }
    /**
     * @inheritdoc
     */
    protected function afterTableCreation()
    {
        $this->insert($this->tableName, [
            'name' => 'Sviluppo',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Eventi',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Ambiente',
        ]);

    }

}
