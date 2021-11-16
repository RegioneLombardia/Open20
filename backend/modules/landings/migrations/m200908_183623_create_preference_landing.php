<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m200805_113023_create_preference_campain
 */
class m200908_183623_create_preference_landing extends AmosMigrationTableCreation
{

    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%preference_landing}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'preference_structure_id' => $this->integer()->defaultValue(null)->comment('Structure'),
            'status' => $this->string()->comment('status'),
            'title' => $this->string()->comment('Title'),
            'description' => $this->text()->comment('Description'),
            'schedule' => $this->text()->comment('Schedule'),
            'image_slider_id' => $this->integer()->comment('Slider images'),
            'video_slider_id' => $this->integer()->comment('Slider videos'),
            'luya_page_id' => $this->integer()->comment('Luya page'),
            'luya_template_id' => $this->integer()->comment('Luya template'),
            'url' => $this->string()->comment('Url'),

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
        $this->addForeignKey('fk_preference_landing_structure_id', $this->tableName, 'preference_structure_id', '{{%preference_structure}}', 'id');
    }
}
