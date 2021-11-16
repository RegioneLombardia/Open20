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
 * Class m160912_084737_create_news_allegati
 */
class m160912_084737_create_news_allegati extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%news_allegati}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'titolo' => $this->string(255)->notNull()->comment('Titolo'),
            'descrizione' => $this->text()->null()->defaultValue(null)->comment('Descrizione'),
            'filemanager_mediafile_id' => $this->integer()->null()->defaultValue(null)->comment('Immagine'),
            'news_id' => $this->integer()->notNull()->comment('News ID'),
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
        $this->addCommentOnTable($this->tableName, 'allegati news');
        $this->addPrimaryKey('', $this->tableName, 'filemanager_mediafile_id');
    }

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_news_allegati_news1_idx', $this->getRawTableName(), 'news_id', '{{%news}}', 'id');
    }
}
