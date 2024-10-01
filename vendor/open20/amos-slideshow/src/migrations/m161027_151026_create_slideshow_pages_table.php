<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m161027_151026_create_slideshow_pages_table
 * Handles the creation for table `slideshow_pages`.
 */
class m161027_151026_create_slideshow_pages_table extends AmosMigrationTableCreation
{
    protected function setTableName()
    {
        $this->tableName = '{{%slideshow_pages}}';
    }

    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'pageContent' => $this->text()->notNull(),
            'ordinal' => $this->smallInteger()->notNull(),
            'slideshow_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->null()->defaultValue(null),
            'updated_at' => $this->dateTime()->null()->defaultValue(null),
            'deleted_at' => $this->dateTime()->null()->defaultValue(null),
            'created_by' => $this->integer()->null()->defaultValue(null),
            'updated_by' => $this->integer()->null()->defaultValue(null),
            'deleted_by' => $this->integer()->null()->defaultValue(null)
        ];
    }

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_' . $this->getRawTableName() . '_slideshow_id', $this->tableName, 'slideshow_id', 'slideshow', 'id');
    }
}
