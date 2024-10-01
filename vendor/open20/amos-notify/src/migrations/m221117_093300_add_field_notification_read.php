<?php

use yii\db\Migration;

class m221117_093300_add_field_notification_read extends Migration
{
    const TABLE = 'notificationread';

    public function safeUp()
    {
       $this->addColumn(self::TABLE, 'notification_type' ,$this->integer()->defaultValue(0)->after('notification_id'));

    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'notification_type');
    }
}
