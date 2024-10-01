<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\videoconference\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m171204_135754_create_table_videoconf_users_mm
 */
class m171204_135754_create_table_videoconf_users_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%videoconf_users_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'videoconf_id' => $this->integer()->notNull(),
            'user_profile_id' => $this->integer()->notNull()
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
        $this->addForeignKey('fk_videoconf_users_mm', $this->getRawTableName(), 'videoconf_id', '{{%videoconf}}', 'id');
        $this->addForeignKey('fk_users_videoconf_mm', $this->getRawTableName(), 'user_profile_id', '{{%user_profile}}', 'id');
    }
}
