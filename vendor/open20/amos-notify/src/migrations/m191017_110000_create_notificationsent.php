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


class m191017_110000_create_notificationsent extends Migration
{
    const TABLE = '{{%notificationsent}}';
    
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'user_id' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'type' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'howmany' => Schema::TYPE_INTEGER . " DEFAULT 1",
                'created_at' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL ",
                'updated_at' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL ",
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->createIndex("idx_1", self::TABLE, ["user_id"]);
            $this->addForeignKey('user_id', self::TABLE, 'user_id', '{{%user}}', 'id', 'NO ACTION', 'NO ACTION');
           
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
        
        return true;
    }
        
}
