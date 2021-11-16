<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\workflow\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

class m170519_134656_create_workflow_transitions_log extends Migration
{
    const TABLE = '{{%amos_workflow_transitions_log}}';

    public function safeUp()
    {
        $tableName = $this->db->getSchema()->getRawTableName(self::TABLE);
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            try {
                $this->createTable(self::TABLE, [
                    'id' => $this->primaryKey(),
                    'classname' => $this->text(255)->defaultValue(null)->comment('Name of the class'),
                    'owner_primary_key' => $this->text(255)->defaultValue(null)->comment('Primary key'),
                    'start_status' => $this->text(255)->defaultValue(null)->comment('Start status'),
                    'end_status' => $this->text(255)->defaultValue(null)->comment('End status'),
                    'created_at' => $this->dateTime()->null()->defaultValue(null),
                    'updated_at' => $this->dateTime()->null()->defaultValue(null),
                    'deleted_at' => $this->dateTime()->null()->defaultValue(null),
                    'created_by' => $this->integer()->null()->defaultValue(null),
                    'updated_by' => $this->integer()->null()->defaultValue(null),
                    'deleted_by' => $this->integer()->null()->defaultValue(null),
                ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            } catch (Exception $e) {
                echo "Error on creation " . $tableName . "\n";
                echo $e->getMessage() . "\n";
                return false;
            }
        } else {
            echo "Table already exist " . $tableName . "\n";
        }

        return true;
    }

    public function safeDown()
    {
        try {
//            $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
            $this->dropTable(self::TABLE);
//            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        } catch (Exception $e) {
            echo "Error on drop table " . self::TABLE . "\n";
            echo $e->getMessage() . "\n";
            return false;
        }

        return true;
    }

}