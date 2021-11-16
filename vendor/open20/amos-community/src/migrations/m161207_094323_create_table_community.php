<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m161207_094323_create_table_community
 */
class m161207_094323_create_table_community extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%community}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'status' => $this->string(255)->null()->comment('Status'),
            'denominazione' => $this->string(255)->notNull()->comment('Denominazione'),
            'descrizione' => $this->text()->null()->comment('Descrizione'),
            'logo_id' => $this->integer()->null()->comment('Logo id'),
            'immagine_copertina_id' => $this->integer()->null()->comment('Immagine copertina id')
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
}
