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
 * Class m200805_114522_create_preference_campain_section
 */
class m200805_114522_create_preference_campain_section extends AmosMigrationTableCreation {

    /**
     * @inheritdoc
     */
    protected function setTableName() {
        $this->tableName = '{{%preference_campain_section}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->null()->defaultValue(null)->comment('Title'),
            'color' => $this->string(255)->null()->defaultValue(null)->comment('Color'),
            'preference_campain_channel_mm_id' => $this->integer()->null()->defaultValue(null)->comment('Preference campain channel mm'),
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
        $this->addForeignKey('fk_preference_campain_channel_mm', $this->tableName, 'preference_campain_channel_mm_id', 'preference_campain_channel_mm', 'id');
    }
}
