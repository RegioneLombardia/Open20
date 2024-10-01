<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;
use yii\db\Schema;

/**
 * Class m181016_084648_create_ticket_categorie
 */
class m181016_084648_create_ticket_categorie extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%ticket_categorie}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'titolo' => $this->string(255)->null()->defaultValue(null)->comment('Titolo'),
            'sottotitolo' => $this->string(255)->null()->defaultValue(null)->comment('Sottotitolo'),
            'descrizione_breve' => $this->string(255)->null()->defaultValue(null)->comment('Descrizione breve'),
            'descrizione' => $this->text()->null()->defaultValue(null)->comment('Descrizione'),
            'abilita_ticket' => "TINYINT(1) DEFAULT '0' COMMENT 'Abilita creazione ticket'",
            'attiva' => "TINYINT(1) DEFAULT '0' COMMENT 'Attiva una categoria'",
            'tecnica' => "TINYINT(1) DEFAULT '0' COMMENT 'Si tratta di una categoria tecnica'",
            'email_tecnica' => $this->string(255)->null()->defaultValue(null)->comment('Indirizzo email per ticket categoria tecnica'),
            'categoria_padre_id' => Schema::TYPE_INTEGER . " COMMENT 'Categoria padre'",
            'filemanager_mediafile_id' => $this->integer()->null()->defaultValue(null)->comment('Immagine'),
            'version' => $this->integer()->null()->defaultValue(null)->comment('Versione numero')
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
        $this->addCommentOnTable($this->tableName, 'categorie per i ticket e le faq dei ticket');
    }

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_ticket_categoria_padre', $this->tableName, 'categoria_padre_id', 'ticket_categorie', 'id');
    }
}
