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
class m210506_112433_create_preference_campain_mailup_import extends AmosMigrationTableCreation {

    /**
     * @inheritdoc
     */
    protected function setTableName() {
        $this->tableName = '{{%preference_campain_mailup_import}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'preference_campain_channel_mm_id' => $this->integer()->null()->defaultValue(null)->comment('Preference FK to campain channel'),
            'mailup_import_id' => $this->integer()->null()->defaultValue(null)->comment('Mailup import id'),
            'response' => $this->text()->null()->defaultValue(null)->comment('response'),
            'active' => $this->boolean()->null()->defaultValue(null)->comment('Last import, the active import'),
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
        $this->addForeignKey('fk_preference_campain_mailup_import_campain_channel_mm', $this->tableName, 'preference_campain_channel_mm_id', 'preference_campain_channel_mm', 'id');
    }

}
