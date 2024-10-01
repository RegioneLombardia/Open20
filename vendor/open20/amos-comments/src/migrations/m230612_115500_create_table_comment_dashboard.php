<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

class m230612_115500_create_table_comment_dashboard extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%comment_dashboard}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'netiquette_platform' => $this->boolean()->notNull()->defaultValue(0)->comment('Enable netiquette for platform plugins'),
            'netiquette_communities' => $this->boolean()->notNull()->defaultValue(0)->comment('Enable netiquette for community contents'),
            'moderation_platform' => $this->boolean()->notNull()->defaultValue(0)->comment('Enable comments moderation for platform plugins'),
            'moderation_communities' => $this->boolean()->notNull()->defaultValue(0)->comment('Enable comments moderation for community contents'),
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
