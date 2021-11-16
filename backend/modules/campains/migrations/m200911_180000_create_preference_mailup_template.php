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
 * Class m200911_180000_create_preference_mailup_template
 */
class m200911_180000_create_preference_mailup_template extends AmosMigrationTableCreation {

    /**
     * @inheritdoc
     */
    protected function setTableName() {
        $this->tableName = '{{%preference_mailup_template}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields() {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'mailup_template_id' => $this->integer(11)->null()->defaultValue(null)->comment('ID MAILUP'),
            'title' => $this->string(255)->null()->defaultValue(null)->comment('Title'),
            'section' => $this->text()->null()->defaultValue(null)->comment('Sezione'),
            'content_center_image' => $this->text()->null()->defaultValue(null)->comment('Contenuto con immagine centrale'),
            'content_left_image' => $this->text()->null()->defaultValue(null)->comment('Contenuto con immagine a sinistra'),
            'content_right_image' => $this->text()->null()->defaultValue(null)->comment('Contenuto con immagine a destra'),
            'separator' => $this->text()->null()->defaultValue(null)->comment('separatore'),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeTableCreation() {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }

}
