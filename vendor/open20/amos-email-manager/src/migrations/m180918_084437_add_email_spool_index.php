<?php

use yii\db\Migration;
use yii\db\Schema;

class m180918_084437_add_email_spool_index extends Migration
{
    const TABLE = '{{%email_template}}';
    const TABLE_SPOOL = '{{%email_spool}}';


    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_SPOOL, true) !== null) {
            $this->createIndex("created_at", self::TABLE_SPOOL, ["created_at"]);
        }
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_SPOOL, true) !== null) {
            $this->dropIndex("created_at", self::TABLE_SPOOL);
        }
    }
}
