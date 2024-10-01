<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m201005_111000_create_table_site_management_community_slider_mm
 */
class m201005_111000_create_table_site_management_community_slider_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%site_management_community_slider_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'community_id' => $this->integer(11)->notNull()->comment('Community'),
            'site_management_slider_id' => $this->integer(11)->notNull()->comment('Slider'),
            'type' => $this->string(255)->notNull()->comment('Type of slider'),
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
