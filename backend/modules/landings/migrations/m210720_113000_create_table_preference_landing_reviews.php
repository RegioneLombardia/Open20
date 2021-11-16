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

class m210720_113000_create_table_preference_landing_reviews extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%preference_landing_rating}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'preference_landing_id' => $this->integer()->defaultValue(null)->comment('Landing'),
            'user_id' => $this->integer()->defaultValue(null)->comment('Utente'),
            'rating' =>  $this->integer()->defaultValue(0)->comment('Rating'),
            'ip' =>  $this->text()->null()->defaultValue(null)->comment('How it work'),
            'maturity' =>  $this->string()->null()->defaultValue(null)->comment('IP'),
        ];

       // $this->addForeignKey('fk_preference_landing_reviews_preference_landing_id',$this->getRawTableName(), 'preference_landing_id', 'preference_landing', 'id');
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
}
