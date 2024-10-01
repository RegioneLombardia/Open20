<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

use yii\db\Migration;

class m160919_091930_tag_models_auth_items_mm extends Migration
{
    private $tabella = null;

    public function __construct()
    {
        $this->tabella = \open20\amos\tag\models\TagModelsAuthItemsMm::tableName();
        parent::__construct();
    }

    public function safeUp()
    {
        $this->createTable($this->tabella, [
            'tag_id' => $this->integer(11)->notNull()->comment('Root'),
            'classname' => $this->string(255)->notNull()->comment('Model'),
            'auth_item' => $this->string(255)->notNull()->comment('Item'),
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);

        $this->addPrimaryKey('pk_tag_models_0', $this->tabella, 'tag_id, classname, auth_item');

        $this->addForeignKey('fk_roots01', $this->tabella, 'tag_id', 'tag', 'id');
        $this->addForeignKey('fk_auth_item01', $this->tabella, 'auth_item', 'auth_item', 'name');
        // remove unused tables
        $tableSchema = Yii::$app->db->schema->getTableSchema('{{%tag_auth_item_mm}}');
        if(!empty($tableSchema)) {
            $this->dropTable('{{%tag_auth_item_mm}}');
        }
        $tableSchema = Yii::$app->db->schema->getTableSchema('{{%tag_models_mm}}');
        if(!empty($tableSchema)) {
            $this->dropTable('{{%tag_models_mm}}');
        }
        return true;
    }

    public function safeDown()
    {
        $this->dropTable($this->tabella);
        return true;
    }
}
