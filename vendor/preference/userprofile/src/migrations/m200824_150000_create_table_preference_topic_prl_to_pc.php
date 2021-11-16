`<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m200824_150000_create_table_preference_topic_prl_to_pc
 */
class m200824_150000_create_table_preference_topic_prl_to_pc extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%preference_topic_prl_to_pc}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'prl' => $this->string(255)->notNull()->comment('Stringa tematica Portale Regione Lombardia'),
            'pc' => $this->string(255)->notNull()->comment('Stringa tematica Preference Centre'),
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
        $this->insert($this->tableName, [
            'prl' => 'Scuola, Università e ricerca',
            'pc' => 'Innovazione',
        ]);

        $this->insert($this->tableName, [
            'prl' => 'Scuola, Università e ricerca',
            'pc' => 'Istruzione, formazione e lavoro',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Tutela Ambientale',
            'pc' => 'Ambiente e sviluppo sostenibile',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Cultura',
            'pc' => 'Cultura, tempo libero e turismo',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Turismo, sport e tempo libero',
            'pc' => 'Cultura, tempo libero e turismo',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Muoversi in Lombardia',
            'pc' => 'Mobilità, trasporti e blocchi del traffico',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Sicurezza e protezione civile',
            'pc' => 'Allerta e info di pubblica utilità',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Ricerca e innovazione per le imprese',
            'pc' => 'Innovazione',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Sicurezza ambientale e alimentare',
            'pc' => 'Ambiente e sviluppo sostenibile',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Imprese culturali e creative',
            'pc' => 'Cultura, tempo libero e turismo',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Imprese di trasporto e logistica',
            'pc' => 'Mobilità, trasporti e blocchi del traffico',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Gestione risorse umane',
            'pc' => 'Istruzione, formazione e lavoro',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Ricerca e innovazione',
            'pc' => 'Innovazione',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Ambiente ed energia',
            'pc' => 'Ambiente e sviluppo sostenibile',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Promozione del turismo',
            'pc' => 'Cultura, tempo libero e turismo',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Trasporti e logistica',
            'pc' => 'Mobilità, trasporti e blocchi del traffico',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Istruzione',
            'pc' => 'Istruzione, formazione e lavoro',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Occupazione e formazione professionale',
            'pc' => 'Istruzione, formazione e lavoro',
        ]);


        $this->insert($this->tableName, [
            'prl' => 'Protezione civile',
            'pc' => 'Allerta e info di pubblica utilità',
        ]);

    }

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
    }
}
