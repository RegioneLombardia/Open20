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
 * Class m170913_075607_create_partnership_profiles_table_partnership_profiles_types_mm
 */
class m170913_075607_create_partnership_profiles_table_partnership_profiles_types_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%partnership_profiles_types_mm}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'partnership_profile_id' => $this->integer()->notNull()->comment('Partnership Profile ID'),
            'partnership_profiles_type_id' => $this->integer()->notNull()->comment('Partnership Profiles Type ID')
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
        $this->createIndex('partnership_profiles_types_index', $this->tableName, ['partnership_profile_id', 'partnership_profiles_type_id']);
    }
    
    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_partnership_profiles_types_partnership_profiles', $this->getRawTableName(), 'partnership_profiles_type_id', 'partnership_profiles_type', 'id');
        $this->addForeignKey('fk_partnership_profiles_partnership_profiles_types', $this->getRawTableName(), 'partnership_profile_id', 'partnership_profiles', 'id');
    }
}
