<?php

use yii\db\Migration;

class m170905_142740_add_index_notification extends Migration
{
    const TABLE = '{{%notification}}';
    const TABLE_READ = '{{%notificationread}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true))
        {

            $this->createIndex("class_name", self::TABLE, ["class_name"]);
            $this->createIndex("channels", self::TABLE, ["channels"]);

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
            $this->dropIndex('class_name',self::TABLE );
            $this->dropIndex('channels',self::TABLE );
        }
        else
        {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
        }

        return true;
    }
}
