<?php

use yii\db\Migration;

class m230926_154700_add_fields_notification_schedule extends Migration {

    const TABLE = 'notification_schedule';

    public function safeUp() {
        $this->addColumn(self::TABLE, 'start_user_id', $this->integer()->defaultValue(null)->after('max_user_id_to_notify'));
    }

    public function safeDown() {
        $this->dropColumn(self::TABLE, 'start_user_id');
    }

}
