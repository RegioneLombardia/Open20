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
 * Class m181023_101049_create_table_profilo_sedi_user_mm
 */
class m181023_101049_create_table_profilo_sedi_user_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%profilo_sedi_user_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'profilo_sedi_id' => $this->integer()->notNull()->comment('Profilo Sedi Id'),
            'user_id' => $this->integer()->notNull()->comment('User Id'),
            'status' => $this->string(255)->null()->defaultValue(null)->comment('Stato'),
            'role' => $this->string(255)->null()->defaultValue(null)->comment('Ruolo')
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
        $this->addCommentOnTable($this->tableName, 'Profilo Sedi User Mm');
        $this->createIndex('profilo_sedi_user_mm_index', $this->tableName, ['profilo_sedi_id', 'user_id']);
    }

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_profilo_sedi_user', $this->getRawTableName(), 'profilo_sedi_id', '{{%profilo_sedi}}', 'id');
        $this->addForeignKey('fk_user_profilo_sedi', $this->getRawTableName(), 'user_id', '{{%user}}', 'id');
    }
}
