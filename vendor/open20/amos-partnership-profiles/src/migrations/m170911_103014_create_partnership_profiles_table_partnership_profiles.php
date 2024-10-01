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
 * Class m170911_103014_create_partnership_profiles_table_partnership_profiles
 */
class m170911_103014_create_partnership_profiles_table_partnership_profiles extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%partnership_profiles}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'status' => $this->string(255)->notNull()->comment('Status'),
            'title' => $this->string(255)->notNull()->comment('Title'),
            'short_description' => $this->text()->notNull()->comment('Short Description'),
            'extended_description' => $this->text()->notNull()->comment('Extended Description'),
            'advantages_innovative_aspects' => $this->text()->notNull()->comment('Advantages and Innovative Aspects'),
            'other_prospect_desired_collab' => $this->string(255)->notNull()->comment('Other prospective / desired collaboration'),
            'expected_contribution' => $this->text()->notNull()->comment('Expected Contribution'),
            'contact_person' => $this->string(255)->null()->defaultValue(null)->comment('Contact Person'),
            'partnership_profile_date' => $this->date()->notNull()->comment('Partnership Profile Date'),
            'expiration_in_months' => $this->smallInteger()->notNull()->comment('Expiration in months'),
            'english_title' => $this->string(255)->null()->defaultValue(null)->comment('English Title'),
            'english_short_description' => $this->text()->null()->defaultValue(null)->comment('English Short Description'),
            'english_extended_description' => $this->text()->null()->defaultValue(null)->comment('English Extended Description'),
            'willingness_foreign_partners' => $this->boolean()->null()->defaultValue(null)->comment('Willingness Foreign Partners'),
            'other_work_language' => $this->string(255)->null()->defaultValue(null)->comment('Other Work Language'),
            'other_development_stage' => $this->string(255)->null()->defaultValue(null)->comment('Other Development Stage'),
            'other_intellectual_property' => $this->string(255)->null()->defaultValue(null)->comment('Other Intellectual Property'),
            'validated_at_least_once' => $this->boolean()->notNull()->defaultValue(0)->comment('Validated At Least Once'),
            
            'partnership_profile_facilitator_id' => $this->integer()->null()->defaultValue(null)->comment('Partnership Profile Facilitator ID'),
            'work_language_id' => $this->integer()->null()->defaultValue(null)->comment('Work Language ID'),
            'development_stage_id' => $this->integer()->null()->defaultValue(null)->comment('Development Stage ID'),
            'intellectual_property_id' => $this->integer()->null()->defaultValue(null)->comment('Intellectual Property ID')
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
        $this->createIndex('partnership_profile_facilitator_index', $this->tableName, 'partnership_profile_facilitator_id');
        $this->createIndex('work_language_index', $this->tableName, 'work_language_id');
        $this->createIndex('development_stage_index', $this->tableName, 'development_stage_id');
        $this->createIndex('intellectual_property_index', $this->tableName, 'intellectual_property_id');
    }
    
    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_partnership_profiles_work_language', $this->getRawTableName(), 'work_language_id', 'work_language', 'id');
        $this->addForeignKey('fk_partnership_profiles_development_stage', $this->getRawTableName(), 'development_stage_id', 'development_stage', 'id');
        $this->addForeignKey('fk_partnership_profiles_intellectual_property', $this->getRawTableName(), 'intellectual_property_id', 'intellectual_property', 'id');
    }
}
