<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\report\migrations
 * @category   Migration
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Handles the creation of table `report`.
 */
class m170524_123150_create_report_table extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%report}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'context_id' => $this->integer()->notNull()->comment('Report Context ID'),
            'classname' => $this->text()->notNull()->comment('Foreign class name'),
            'type' => $this->integer()->notNull()->comment('Report Type ID'),
            'content' => $this->text()->notNull()->comment('Report content'),
            'status' =>  $this->integer()->notNull()->defaultValue(0)->comment('Report Status ( 0 unread, 1 read )'),
            'creator_id' => $this->integer()->notNull()->comment('Creator User ID'),
            'read_at' => $this->dateTime()->null()->defaultValue(null)->comment('Report read at'),
            'read_by' => $this->integer()->notNull()->comment('Read User ID'),
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
