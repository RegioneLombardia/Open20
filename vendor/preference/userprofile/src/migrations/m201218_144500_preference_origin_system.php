<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use open20\amos\core\migration\AmosMigrationTableCreation;
use yii\db\Migration;

/**
 * Class m200827_115315_add_user_profile_structure_id_field
 */
class m201218_144500_preference_origin_system extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%preference_origin_system}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->null()->comment('Link al topic'),
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
       $this->insert($this->tableName, ['id' => '1', 'name' => 'web']);
    }

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
    }

}
