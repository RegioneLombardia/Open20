<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */
use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m230704_094500_create_elasticsearch_token
 */
class m230704_094500_create_elasticsearch_token extends AmosMigrationTableCreation {

    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%elasticsearch_token%}}';
    }

    /**
     * set table fields
     *
     * @return void
     */
    protected function setTableFields() {

        $this->tableFields = [
            // PK
            'id' => $this->primaryKey(),
            // COLUMNS
            'token' => $this->string()->null()->defaultValue(null)->comment('Token'),
            'url' => $this->text()->null()->defaultValue(null)->comment('Token'),
            'time' => $this->integer()->null()->defaultValue(null)->comment('Time'),
            'consumed' => $this->integer()->null()->defaultValue(0)->comment('Consumed'),
        ];
    }

    /**
     * Timestamp
     */
    protected function beforeTableCreation() {

        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    } 

    public function afterTableCreation() {
        $this->createIndex('idx_token', $this->tableName, 'token');
        $this->createIndex('idx_consumed', $this->tableName, 'consumed');
        $this->createIndex('idx_deleted_at', $this->tableName, 'deleted_at');
        $this->createIndex('idx_url', $this->tableName, 'url');
    }
}
 