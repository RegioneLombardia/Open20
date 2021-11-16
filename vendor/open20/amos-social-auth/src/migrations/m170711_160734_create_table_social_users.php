<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\socialauth\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m170711_160734_create_table_social_users
 */
class m170711_160734_create_table_social_users extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%social_users}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->null()->comment('Birth Year'),
            'identifier' => $this->string(255)->notNull()->comment('Unique Identiffier for Provider'),
            'profileURL' => $this->string(255)->null()->comment('Provider Profile Url'),
            'webSiteURL' => $this->string(255)->null()->comment('Web Site Url'),
            'photoURL' => $this->string(255)->null()->comment('Photo Url'),
            'displayName' => $this->string(60)->null()->comment('User Display Name'),
            'description' => $this->string(255)->null()->comment('User Description'),
            'firstName' => $this->string(60)->null()->comment('First Name'),
            'lastName' => $this->string(60)->null()->comment('Last Name'),
            'gender' => $this->string(20)->null()->comment('Gender'),
            'language' => $this->string(20)->null()->comment('Language'),
            'email' => $this->string(255)->null()->comment('Email'),
            'emailVerified' => $this->string(255)->null()->comment('Email Veriffied'),
            'phone' => $this->string(45)->null()->comment('Phone Number'),
            'address' => $this->string(255)->null()->comment('Address'),
            'country' => $this->string(60)->null()->comment('Country'),
            'region' => $this->string(60)->null()->comment('Region'),
            'city' => $this->string(60)->null()->comment('City'),
            'zip' => $this->integer(10)->null()->comment('Zip Code'),
            'provider' => $this->string(20)->notNull()->comment('Provider Name'),
            'age' => $this->integer(3)->null()->comment('Age'),
            'birthDay' => $this->integer(2)->null()->comment('Birth Day'),
            'birthMonth' => $this->integer(2)->null()->comment('Birth Month'),
            'birthYear' => $this->integer(4)->null()->comment('Birth Year'),
            'job_title' => $this->string(45)->null()->comment('Job Title'),
            'organization_name' => $this->string(45)->null()->comment('Organization Name')
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
        $this->addForeignKey('fk_user_social_users', $this->getRawTableName(), 'user_id', '{{%user}}', 'id');
    }
}
