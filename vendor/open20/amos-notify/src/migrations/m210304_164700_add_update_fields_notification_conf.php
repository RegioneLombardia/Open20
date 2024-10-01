<?php

use yii\db\Migration;

class m210304_164700_add_update_fields_notification_conf extends Migration
{
    const TABLE = 'notificationconf';

    public function safeUp()
    {
       $this->addColumn(self::TABLE, 'last_update_frequency' ,$this->dateTime()->defaultValue(null)->after('profilo_successo_email'));

    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'last_update_frequency');
    }
}
