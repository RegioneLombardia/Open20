<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m170911_103039_create_partnership_profiles_table_expressions_of_interest
 */
class m170911_103039_create_partnership_profiles_table_expressions_of_interest extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%expressions_of_interest}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'status' => $this->string(255)->notNull()->comment('Status'),
            'partnership_offered' => $this->string(255)->notNull()->comment('Partnership offered'),
            'additional_information' => $this->string(255)->notNull()->comment('Additional information'),
            'clarifications' => $this->string(255)->notNull()->comment('Clarifications'),
            'partnership_profile_id' => $this->integer()->notNull()->comment('Partnership Profile ID')
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
        $this->createIndex('partnership_profiles_index', $this->tableName, 'partnership_profile_id');
    }
    
    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_expressions_of_interest_partnership_profiles', $this->getRawTableName(), 'partnership_profile_id', 'partnership_profiles', 'id');
    }
}
