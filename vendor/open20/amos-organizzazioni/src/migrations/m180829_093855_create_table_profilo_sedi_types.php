<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m180829_093855_create_table_profilo_sedi_types
 */
class m180829_093855_create_table_profilo_sedi_types extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%profilo_sedi_types}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->comment('Name'),
            'active' => $this->boolean()->notNull()->defaultValue(1)->comment('Active'),
            'read_only' => $this->boolean()->notNull()->defaultValue(0)->comment('Read Only'),
            'order' => $this->smallInteger()->null()->defaultValue(null)->comment('Order')
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
        $this->addCommentOnTable($this->tableName, 'Profilo Sedi Types');
    }
}
