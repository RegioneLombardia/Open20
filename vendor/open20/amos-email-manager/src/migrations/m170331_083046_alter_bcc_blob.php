<?php

use yii\db\Migration;

class m170331_083046_alter_bcc_blob extends Migration
{
    const TABLE_SPOOL = '{{%email_spool}}';

    private $tableName;

    public function up()
    {
        try {
            $this->alterColumn($this->tableName, 'bcc', 'LONGBLOB DEFAULT NULL');
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }

    }

    public function down()
    {
        echo "m170331_083046_alter_bcc_blob.\n";

        return false;
    }

    public function init()
    {
        parent::init();
        $this->tableName = $this->db->getSchema()->getRawTableName(self::TABLE_SPOOL);
    }
}
