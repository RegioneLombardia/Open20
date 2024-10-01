<?php
/**
 */

namespace yii\queue\db\migrations;

use yii\db\Migration;

/**
 * Example of migration for queue message storage.
 *
 */
class M170307170300Later extends Migration
{
    public $tableName = '{{%queue}}';


    public function up()
    {
        $this->addColumn($this->tableName, 'timeout', $this->integer()->defaultValue(0)->notNull()->after('created_at'));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'timeout');
    }
}
