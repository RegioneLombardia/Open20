<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

use yii\db\Migration;
use yii\db\Schema;

class m180404_083640_add_session_in_db extends Migration
{
    
    const TABLE = '{{%session}}';
    
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_CHAR . "(40) NOT NULL PRIMARY KEY",
                'expire' => Schema::TYPE_INTEGER ,
                'data' => "BLOB",
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null);
           
        }
        else
        {
            echo "No creation performed because the table already exists";
        }
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null)
        {
            $this->dropTable(self::TABLE);
        }
        else
        {
            echo "No deletion is performed because the table does not exist";
        }

        return true;
    }
}
