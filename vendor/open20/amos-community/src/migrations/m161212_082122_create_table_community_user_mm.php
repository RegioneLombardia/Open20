<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m161212_082122_create_table_community_user_mm
 */
class m161212_082122_create_table_community_user_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%community_user_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'community_id' => $this->integer()->notNull()->comment('Community ID'),
            'user_id' => $this->integer()->notNull()->comment('User ID')
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
        $this->addForeignKey('fk_community_user_mm_community', $this->tableName, 'community_id', 'community', 'id');
        $this->addForeignKey('fk_community_user_mm_user', $this->tableName, 'user_id', 'user', 'id');
    }
}
