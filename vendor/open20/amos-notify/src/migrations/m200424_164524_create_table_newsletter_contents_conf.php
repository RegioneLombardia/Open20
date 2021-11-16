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
 * Class m200424_164524_create_table_newsletter_contents_conf
 */
class m200424_164524_create_table_newsletter_contents_conf extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%notification_newsletter_contents_conf}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'tablename' => $this->string(255)->notNull()->comment('Tablename'),
            'classname' => $this->string(255)->notNull()->comment('Classname'),
            'label' => $this->string(255)->notNull()->comment('Label'),
            'order' => $this->smallInteger()->unsigned()->notNull()->comment('Order'),
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
