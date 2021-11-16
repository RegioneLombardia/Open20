<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

class m210730_164700_create_table_preference_landing_links extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%preference_landing_links}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'preference_landing_id' => $this->integer()->defaultValue(null)->comment('Landing'),
            'sort_value' => $this->integer()->defaultValue(null)->comment('Ordinamento'),
            'title' =>  $this->string()->null()->defaultValue(null)->comment('titolo'),
            'url' =>  $this->string()->null()->defaultValue(null)->comment('url'),
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
        $this->addCommentOnTable($this->tableName, 'Rating for landing');
    }


    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_preference_landing_links_preference_landing_id', $this->getRawTableName(), 'preference_landing_id', 'preference_landing', 'id');
        parent::addForeignKeys();
    }

    public function dropForeignKey($name, $table)
    {
        $this->dropForeignKey('fk_preference_landing_links_preference_landing_id');
    }

}
