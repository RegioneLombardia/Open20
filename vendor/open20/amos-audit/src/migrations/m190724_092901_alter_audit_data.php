<?php

use open20\amos\audit\components\Migration;

use yii\db\Schema;

class m190724_092901_alter_audit_data extends Migration
{
    const TABLE = '{{%audit_data}}';

    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $this->alterColumn(self::TABLE, 'data', 'LONGBLOB');
        } elseif ($this->db->driverName === 'sqlsrv') {
            $this->alterColumn(self::TABLE, 'data', 'VARBINARY(MAX)');
        } else {
            $this->alterColumn(self::TABLE, 'data', 'BYTEA');
        }
    }
}
