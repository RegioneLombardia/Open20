<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */
use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m200615_155715_create_table_modelli
 */
class m200615_155715_create_table_modelli extends AmosMigrationTableCreation {

    /**
     * @inheritdoc
     */
    protected function setTableName() {
        $this->tableName = '{{%sondaggi_modelli_predefiniti}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'classname' => $this->string(255)->null()->defaultValue(null)->comment('Nome classe modello da utilizzare per le Risposte'),
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


}
