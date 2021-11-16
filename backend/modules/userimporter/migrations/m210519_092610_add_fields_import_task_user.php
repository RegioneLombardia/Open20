<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m210519_092610_add_fields_import_task_user`.
 */
class m210519_092610_add_fields_import_task_user extends Migration
{
    const TABLE = "user_import_task_user";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'mailup_import_id', $this->integer()->null()->defaultValue(null)->after('user_id'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE, 'mailup_import_id');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
