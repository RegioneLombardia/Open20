<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m170315_121500_create_table_event_type_context
 */
class m210408_160430_create_table_event_configurations extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%event_configurations}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'from_days_to_now_visibility' => $this->integer()->comment('Days visibility'),
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
        $this->insert($this->tableName, ['id' => 1, 'from_days_to_now_visibility' => null]);
    }


}
