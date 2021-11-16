<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m210519_092600_add_fields_import_task`.
 */
class m210519_092600_add_fields_import_task extends Migration
{
    const TABLE = "user_import_task";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'mailup_errors', $this->integer()->defaultValue(0)->after('mailup_sending_id'));
        $this->addColumn(self::TABLE, 'mailup_error_message', $this->string(255)->defaultValue(null)->after('mailup_sending_id'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE, 'mailup_errors');
        $this->dropColumn(self::TABLE, 'mailup_error_message');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
