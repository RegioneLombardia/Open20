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
 * Class m200805_113933_create_preference_campain_channel_mm
 */
class m200805_113933_create_preference_campain_channel_mm extends AmosMigrationTableCreation {

    /**
     * @inheritdoc
     */
    protected function setTableName() {
        $this->tableName = '{{%preference_campain_channel_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'preference_campain_id' => $this->integer()->null()->defaultValue(null)->comment('Preference campain'),
            'preference_channel_id' => $this->integer()->null()->defaultValue(null)->comment('Preference channel'),
            'start_date' => $this->date()->defaultValue(null)->comment('Start date'),
            'end_date' => $this->date()->defaultValue(null)->comment(' End date'),
            'title' => $this->string(255)->null()->defaultValue(null)->comment('Title'),
            'subtitle' => $this->string(255)->null()->defaultValue(null)->comment('Subtitle'),
            'subject' => $this->string(255)->null()->defaultValue(null)->comment('Subject'),
            'status' => $this->string(255)->null()->defaultValue(null)->comment('Staus'),
            'template_id' => $this->integer()->null()->defaultValue(null)->comment('Template Id'),
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
        $this->addForeignKey('fk_preference_campain', $this->tableName, 'preference_campain_id', 'preference_campain', 'id');
        $this->addForeignKey('fk_preference_channel', $this->tableName, 'preference_channel_id', 'preference_channel', 'id');
    }
}
