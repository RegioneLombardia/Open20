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
 * Class m200805_121544_create_preference_campain_tag_mm
 */
class m200805_121544_create_preference_campain_tag_mm extends AmosMigrationTableCreation {

    /**
     * @inheritdoc
     */
    protected function setTableName() {
        $this->tableName = '{{%preference_campain_tag_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'preference_campain_id' => $this->integer()->null()->defaultValue(null)->comment('Preference campain'),
            'tag_id' => $this->integer()->null()->defaultValue(null)->comment('Tag'),
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
    protected function addForeignKeys() {
        $this->addForeignKey('fk_preference_campain_tag', $this->tableName, 'preference_campain_id', 'preference_campain', 'id');
        $this->addForeignKey('fk_tag', $this->tableName, 'tag_id', 'tag', 'id');
    }
}
