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
 * Class m161207_102449_create_table_community_tipologia_community_mm
 */
class m161207_102449_create_table_community_tipologia_community_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%community_tipologia_community_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'community_id' => $this->integer()->notNull()->comment('Community ID'),
            'tipologia_community_id' => $this->integer()->notNull()->comment('Tipologia Community ID')
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
        $this->addForeignKey('fk_community_tipologia_community_mm10', $this->tableName, 'community_id', 'community', 'id');
        $this->addForeignKey('fk_tipologia_community_community_mm10', $this->tableName, 'tipologia_community_id', 'tipologia_community', 'id');
    }
}
