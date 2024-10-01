<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard
 * @category   CategoryName
 */
use yii\db\Migration;
use yii\db\Schema;

/**
 * This migration adds column id to amos_widgets table in order to use an integer as id instead of classname (string type column)
 * consequently add column and foreign key referencing amos_widgets id in table amos_user_dashboards_widget_mm
 *
 * Class m171212_090003_alter_tables
 */
class m171212_090003_alter_tables extends Migration
{
    const TABLE_MM = '{{%amos_user_dashboards_widget_mm}}';
    const TABLE    = '{{%amos_widgets}}';
    const FAQ_TABLE = '{{%faq_amos_widgets_mm}}';

    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Use this instead of function up().
     */
    public function safeUp()
    {
        try {
            $tableSchema = $this->db->getSchema()->getTableSchema(self::FAQ_TABLE);
            if(!is_null($tableSchema)){
                $keys = $tableSchema->foreignKeys;
                if(array_key_exists('fk_faq_amos_widgets_mm_amos_widgets1_idx', $keys)){
                    $this->dropForeignKey('fk_faq_amos_widgets_mm_amos_widgets1_idx',self::FAQ_TABLE );
                }
            }
            
            $foreignKeys = $this->db->getSchema()->getTableSchema(self::TABLE_MM)->foreignKeys;
            if(count($foreignKeys)){
                foreach ($foreignKeys as $key => $foreignKey){
                    $this->dropForeignKey($key, self::TABLE_MM);
                }
            }
            $primaryKey = $this->db->getSchema()->getTableSchema(self::TABLE)->primaryKey;
            if(count($primaryKey)) {
                $this->dropPrimaryKey($primaryKey[0], self::TABLE);
            }
            $primaryKeyMm = $this->db->getSchema()->getTableSchema(self::TABLE_MM)->primaryKey;
            if(count($primaryKeyMm)) {
                $this->dropPrimaryKey($primaryKeyMm[0], self::TABLE_MM);
            }

            $table = $this->db->getTableSchema(self::TABLE);
            if(!isset($table->columns['id'])) {
                $this->addColumn(self::TABLE, 'id', $this->primaryKey()->append('AUTO_INCREMENT')->first());
            }
            $tableMm = $this->db->getTableSchema(self::TABLE_MM);
            if(!isset($tableMm->columns['amos_widgets_id'])) {
                $this->addColumn(self::TABLE_MM, 'amos_widgets_id', $this->integer()->null()->defaultValue(null)->first());
            }
            $this->addPrimaryKey('pk_amos_user_dashboards_widget_mm', self::TABLE_MM, ['amos_user_dashboards_id', 'amos_widgets_id'] );
            $this->addForeignKey('fk_amos_widgets_id', self::TABLE_MM, 'amos_widgets_id', self::TABLE, 'id');
            $this->addForeignKey('fk_amos_user_dashboards_id', self::TABLE_MM, 'amos_user_dashboards_id', 'amos_user_dashboards', 'id');

        } catch (Exception $e) {
            echo "Errore durante la modifica delle tabelle.\n";
            echo $e->getMessage()."\n";
            return false;
        }
        return true;
    }

    /**
     * Use this instead of function down().
     */
    public function safeDown()
    {
        try {
            $foreignKeys = $this->db->getSchema()->getTableSchema(self::TABLE_MM)->foreignKeys;
            if(count($foreignKeys)){
                foreach ($foreignKeys as $key => $foreignKey){
                    $this->dropForeignKey($key, self::TABLE_MM);
                }
            }
            $primaryKeyMm = $this->db->getSchema()->getTableSchema(self::TABLE_MM)->primaryKey;
            if(count($primaryKeyMm)) {
                $this->dropPrimaryKey($primaryKeyMm[0], self::TABLE_MM);
            }
            $table = $this->db->getTableSchema(self::TABLE);
            if(isset($table->columns['id'])) {
                $this->dropColumn(self::TABLE, 'id');
            }
            $tableMm = $this->db->getTableSchema(self::TABLE_MM);
            if(isset($tableMm->columns['amos_widgets_id'])) {
                $this->dropColumn(self::TABLE_MM, 'amos_widgets_id');
            }
            $this->addPrimaryKey('pk_amos_widgets', self::TABLE, ['classname'] );
            $this->addPrimaryKey('pk_amos_user_dashboards_widget_mm', self::TABLE_MM, ['amos_user_dashboards_id', 'amos_widgets_classname'] );

            $this->addForeignKey('fk_amos_widgets_classname', self::TABLE_MM, 'amos_widgets_classname', self::TABLE, 'classname');
            $this->addForeignKey('fk_amos_user_dashboards_id', self::TABLE_MM, 'amos_user_dashboards_id', 'amos_user_dashboards', 'id');
        } catch (Exception $e) {
            echo "Errore durante il revert della modifica della tabella ".$this->tableName."\n";
            echo $e->getMessage()."\n";
            return false;
        }

        return true;
    }
}