<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notify\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m200424_184914_create_table_newsletter_contents
 */
class m200424_184914_create_table_newsletter_contents extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%notification_newsletter_contents}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'newsletter_id' => $this->integer()->notNull()->comment('Newsletter Id'),
            'newsletter_contents_conf_id' => $this->integer()->notNull()->comment('Newsletter Contents Conf Id'),
            'content_id' => $this->integer()->notNull()->comment('Content Id'),
            'order' => $this->smallInteger()->unsigned()->notNull()->comment('Order'),
        ];
        $this->setAddCreatedUpdatedFields(true);
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
        $this->createIndex('newsletter_contents_all_index', $this->tableName, ['newsletter_id', 'newsletter_contents_conf_id', 'content_id']);
        $this->createIndex('newsletter_contents_index', $this->tableName, ['newsletter_id', 'newsletter_contents_conf_id']);
        $this->createIndex('newsletter_index', $this->tableName, ['newsletter_id']);
    }

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_newsletter_contents_newsletter', $this->tableName, 'newsletter_id', 'notification_newsletter', 'id');
        $this->addForeignKey('fk_newsletter_contents_newsletter_contents_conf', $this->tableName, 'newsletter_contents_conf_id', 'notification_newsletter_contents_conf', 'id');
    }
}
