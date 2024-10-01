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
 * Class m230418_092500_create_table_prodotti_servizi_destinatari_mm
 */
class m230418_092500_create_table_prodotti_servizi_destinatari_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%prodotti_servizi_destinatari_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'prodotto_id' => $this->integer()->notNull()->comment('ID Prodotto-Servizio'),
            'destinatario_id' => $this->integer()->notNull()->comment('ID Destinatario')
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
        $this->addForeignKey('fk_prodotti_servizi_destinatari_mm_prodotto', $this->tableName, 'prodotto_id', 'prodotti_servizi', 'id');
        $this->addForeignKey('fk_prodotti_servizi_destinatari_mm_destinatario', $this->tableName, 'destinatario_id', 'prodotti_destinatari', 'id');
    }
}
