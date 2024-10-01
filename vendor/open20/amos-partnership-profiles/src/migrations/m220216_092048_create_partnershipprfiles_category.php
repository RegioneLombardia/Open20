<?php

/**
 * Aria S.p.A.
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
class m220216_092048_create_partnershipprfiles_category extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%partnership_profiles_category}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->null()->defaultValue(null)->comment('Titolo'),
            'subtitle' => $this->string(255)->null()->defaultValue(null)->comment('Sottotitolo'),
            'short_description' => $this->string(255)->null()->defaultValue(null)->comment('Descrizione breve'),
            'description' => $this->text()->null()->defaultValue(null)->comment('Descrizione'),
            'color_text' => $this->string()->null()->defaultValue(null)->comment('Color text'),
            'color_background' => $this->string()->null()->defaultValue(null)->comment('Color background'),
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
        $this->addCommentOnTable($this->tableName, 'categorie proposte di collaborazione');
    }
}
