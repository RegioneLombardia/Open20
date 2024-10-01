<?php
/**
 */

namespace yii\queue\db\migrations;

use yii\db\Migration;

/**
 * Example of migration for queue message storage.
 *
 */
class M211218163000JobQueueSize extends Migration
{
    public $tableName = '{{%queue}}';


    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $this->alterColumn($this->tableName, 'job', 'LONGBLOB NOT NULL');
        }
    }

    public function down()
    {
        if ($this->db->driverName === 'mysql') {
            $this->alterColumn($this->tableName, 'job', $this->binary()->notNull());
        }
    }
}
