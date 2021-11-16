<?php

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
 * Class m160912_084648_create_news_categorie
 */
class m160912_084648_create_news_categorie extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%news_categorie}}';
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
        $this->addCommentOnTable($this->tableName, 'categorie news');
    }
}
