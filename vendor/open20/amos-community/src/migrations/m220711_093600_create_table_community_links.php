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
 * Class m220711_093600_create_table_community_links
 */
class m220711_093600_create_table_community_links extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%community_links}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'titolo' => $this->string(255)->notNull()->comment('Titolo del link'),
            'link' => $this->string(255)->notNull()->comment('link'),
            'data_pubblicazione' => $this->dateTime()->defaultValue(null)->comment('Data di pubblicazione'),
            'community_id' => $this->integer()->notNull()->comment('Community ID'),
            'creatore_id' => $this->integer()->notNull()->comment('Utente creatore ID')
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
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_community', $this->tableName, 'community_id', 'community', 'id');
        $this->addForeignKey('fk_community_user', $this->tableName, 'creatore_id', 'user', 'id');
    }
}
