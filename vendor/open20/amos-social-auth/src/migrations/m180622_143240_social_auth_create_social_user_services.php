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
 * Class m180622_143240_social_auth_create_social_user_services
 */
class m180622_143240_social_auth_create_social_user_services extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%social_user_services}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'social_users_id' => $this->integer(11)->notNull()->comment('Social User Id'),
            'service' => $this->string(255)->notNull()->comment('Service name'),
            'access_token' => $this->string(255)->null()->comment('Service Access Token'),
            'token_type' => $this->string(255)->null()->comment('Token type'),
            'expires_in' => $this->integer(11)->null()->comment('Access token expiration'),
            'refresh_token' => $this->string(255)->null()->comment('Refresh token'),
            'token_created' => $this->integer(11)->null()->comment('created_at'),
            'service_id' =>  $this->string(255)->null()->comment('Service ID'),
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
        $this->addForeignKey('fk_social_user_services_social_users', $this->getRawTableName(), 'social_users_id', '{{%social_users}}', 'id');
    }
}
