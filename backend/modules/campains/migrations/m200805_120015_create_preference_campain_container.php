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
 * Class m200805_120015_create_preference_campain_container
 */
class m200805_120015_create_preference_campain_container extends AmosMigrationTableCreation {

    /**
     * @inheritdoc
     */
    protected function setTableName() {
        $this->tableName = '{{%preference_campain_container}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'separator_title' => $this->string(255)->null()->defaultValue(null)->comment('Title'),
            'content_image_path' => $this->string(255)->null()->defaultValue(null)->comment('Content image path'),
            'content_align' => $this->string(255)->null()->defaultValue(null)->comment('Content align'),
            'content_highlight_flag' => $this->integer(1)->defaultValue(0)->comment('Content highlight flag'),
            'content_title' => $this->string(255)->null()->defaultValue(null)->comment('Content title'),
            'content_text' => $this->text()->null()->defaultValue(null)->comment('Content text'),
            'content_news_link' => $this->string(255)->null()->defaultValue(null)->comment('Content news link'),
            'content_facebook_link' => $this->string(255)->null()->defaultValue(null)->comment('Content facebook link'),
            'content_twitter_link' => $this->string(255)->null()->defaultValue(null)->comment('Content twitter link'),
            'content_linkedin_link' => $this->string(255)->null()->defaultValue(null)->comment('Content linkedIn link'),
            'preference_campain_section_id' => $this->integer()->null()->defaultValue(null)->comment('Preference campain section'),
            'preference_campain_container_type_id' => $this->integer()->null()->defaultValue(null)->comment('Preference campain section type'),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeTableCreation() {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }
    
    /**
     * @inheritdoc
     */
    protected function addForeignKeys() {
        $this->addForeignKey('fk_preference_campain_section', $this->tableName, 'preference_campain_section_id', 'preference_campain_section', 'id');
        $this->addForeignKey('fk_preference_campain_container_type', $this->tableName, 'preference_campain_container_type_id', 'preference_campain_container_type', 'id');
    }
}
