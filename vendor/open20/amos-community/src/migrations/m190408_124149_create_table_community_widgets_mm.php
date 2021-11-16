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
 * Class m161207_102449_create_table_community_tipologia_community_mm
 */
class m190408_124149_create_table_community_widgets_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%community_amos_widgets_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'community_id' => $this->integer()->notNull()->comment('Community'),
            'amos_widgets_id' => $this->integer()->notNull()->comment('Widget'),
            'widget_label' => $this->string()->comment('Label widget'),
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
        $this->addForeignKey('fk_community_widget_mm-community_id1', $this->tableName, 'community_id', 'community', 'id');
        $this->addForeignKey('fk_community_widget_mm_amos_widgets_id1', $this->tableName, 'amos_widgets_id', 'amos_widgets', 'id');
    }
}
