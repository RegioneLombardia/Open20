<?php

use yii\db\Migration;
use yii\db\Schema;

class m170724_095325_notificationconf extends Migration
{
    const TABLE = '{{%notificationconf}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'user_id' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'email' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'sms' =>Schema::TYPE_INTEGER . " DEFAULT NULL",
                'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL ",
                'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL ",
                'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL ",
                'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Created by'",
                'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Updated by'",
                'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Deleted by'",
                'version' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Version number'"
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->createIndex("idx_1", self::TABLE, ["user_id"]);

        }
        else
        {
            echo "No creation is performed since the table already exists'";
        }
        return true;
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
