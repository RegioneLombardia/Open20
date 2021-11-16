`<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m200623_100000_create_table_preference_target_channel_mm
 */
class m200719_140000_create_table_preference_topic_channel_frequency extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%preference_topic_channel_frequency}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'preference_channel_id' => $this->integer(11)->notNull()->comment('FK al canale di invio notifiche'),
            'preference_sending_frequency_id' => $this->integer(11)->null()->comment('FK al alle frequenze di invio'),
            'topic_code' => $this->string(60)->null()->comment('Link al topic'),
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

    /**
     * @inheritdoc
     */
    protected function afterTableCreation()
    {
       // Nothing to do
    }

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_topic_channel_frequency_preference_channel_1', $this->tableName, 'preference_channel_id', 'preference_channel', 'id');
        $this->addForeignKey('fk_topic_channel_frequency_sending_frequency_1', $this->tableName, 'preference_sending_frequency_id', 'preference_sending_frequency', 'id');
    }

}

