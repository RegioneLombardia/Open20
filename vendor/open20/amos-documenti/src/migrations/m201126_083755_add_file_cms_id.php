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

class m201126_083755_add_file_cms_id  extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName() {
        $this->tableName = '{{%documenti}}';
    }

    public function up() {
        $this->addColumn($this->tableName, 'file_cms_id', $this->integer()->null()->defaultValue(null)->comment('File Cms Id'));
    }

    public function down() {
        $this->dropColumn($this->tableName, 'file_cms_id');
    }
}
