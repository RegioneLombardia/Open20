<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m170623_123500_create_table_admin_user_profile_area
 */
class m170623_123500_create_table_admin_user_profile_area extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%user_profile_area}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Name'),
            'enabled' => $this->boolean()->notNull()->comment('Enabled'),
            'order' => $this->smallInteger()->notNull()->comment('Order')
        ];
    }
    
    /**
     * @inheritdoc
     */
    protected function afterTableCreation()
    {
        $this->insert($this->tableName, ['id' => 1, 'name' => 'Administration', 'enabled' => 1, 'order' => 10]);
        $this->insert($this->tableName, ['id' => 2, 'name' => 'Commercial', 'enabled' => 1, 'order' => 20]);
        $this->insert($this->tableName, ['id' => 3, 'name' => 'ICT', 'enabled' => 1, 'order' => 30]);
        $this->insert($this->tableName, ['id' => 4, 'name' => 'Management', 'enabled' => 1, 'order' => 40]);
        $this->insert($this->tableName, ['id' => 5, 'name' => 'Marketing and Management', 'enabled' => 1, 'order' => 50]);
        $this->insert($this->tableName, ['id' => 6, 'name' => 'Production', 'enabled' => 1, 'order' => 60]);
        $this->insert($this->tableName, ['id' => 7, 'name' => 'Research and development', 'enabled' => 7, 'order' => 70]);
        $this->insert($this->tableName, ['id' => 8, 'name' => 'Human resources', 'enabled' => 1, 'order' => 80]);
        $this->insert($this->tableName, ['id' => 9, 'name' => 'Other', 'enabled' => 1, 'order' => 1000]);
    }
}
