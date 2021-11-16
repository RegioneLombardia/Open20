<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notify
 * @category   CategoryName
 */


use yii\db\Migration;
use yii\db\Schema;


class m170209_173549_init_notify extends Migration
{
    const TABLE = '{{%notification}}';
    const TABLE_READ = '{{%notificationread}}';
    
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'user_id' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'channels' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'content_id' =>Schema::TYPE_INTEGER . " DEFAULT NULL",
                'class_name' => Schema::TYPE_STRING . "(255) DEFAULT NULL",
                'created_at' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL ",
                'updated_at' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL ",
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->createIndex("idx_1", self::TABLE, ["user_id","content_id"]);
           
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }
        
        if ($this->db->schema->getTableSchema(self::TABLE_READ, true) === null)
        {
            $this->createTable(self::TABLE_READ, [
                'id' => Schema::TYPE_PK,
                'user_id' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'notification_id' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'created_at' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
           $this->createIndex("idx_1", self::TABLE_READ, ["user_id","notification_id"]);
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
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
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
        }
        
        if ($this->db->schema->getTableSchema(self::TABLE_READ, true) !== null)
        {
            $this->dropTable(self::TABLE_READ);
        }
        else
        {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
        }
        
        return true;
    }
        
}
