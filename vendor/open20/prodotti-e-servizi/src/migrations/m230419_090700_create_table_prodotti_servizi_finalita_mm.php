<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m230419_090700_create_table_prodotti_servizi_finalita_mm
 */
class m230419_090700_create_table_prodotti_servizi_finalita_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%prodotti_servizi_finalita_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'prodotto_id' => $this->integer()->notNull()->comment('ID Prodotto-Servizio'),
            'finalita_id' => $this->integer()->notNull()->comment('ID Finalita')
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
        $this->addForeignKey('fk_prodotti_servizi_finalita_mm_prodotto', $this->tableName, 'prodotto_id', 'prodotti_servizi', 'id');
        $this->addForeignKey('fk_prodotti_servizi_finalita_mm_finalita', $this->tableName, 'finalita_id', 'prodotti_finalita', 'id');
    }
}