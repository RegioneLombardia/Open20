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
 * Class m170913_083045_create_partnership_profiles_table_partnership_profiles_countries_mm
 */
class m170913_083045_create_partnership_profiles_table_partnership_profiles_countries_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%partnership_profiles_countries_mm}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'partnership_profile_id' => $this->integer()->notNull()->comment('Partnership Profiles ID'),
            'country_id' => $this->integer()->notNull()->comment('Country ID')
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
        $this->createIndex('countries_index', $this->tableName, ['partnership_profile_id', 'country_id']);
    }
    
    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_countries_partnership_profiles', $this->getRawTableName(), 'country_id', 'istat_nazioni', 'id');
        $this->addForeignKey('fk_partnership_profiles_countries', $this->getRawTableName(), 'partnership_profile_id', 'partnership_profiles', 'id');
    }
}
