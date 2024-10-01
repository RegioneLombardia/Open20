<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    ersaf\documents
 * @category   CategoryName
 */
use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Description of m201112_163755_add_folderCmsId_column_to_Documenti
 */
class m201112_163755_add_folderCmsId_column_to_Documenti extends AmosMigrationTableCreation {

    /**
     * @inheritdoc
     */
    protected function setTableName() {
        $this->tableName = '{{%documenti}}';
    }

    public function up() {
        $this->addColumn($this->tableName, 'folder_cms_id', $this->integer()->null()->defaultValue(null)->comment('Folder Cms Id'));
    }

    public function down() {
        $this->dropColumn($this->tableName, 'folder_cms_id');
    }

}
