<?php

use open20\amos\events\models\EventCategory;
use yii\db\Migration;

class m200515_164013_add_field_in_event_manage_waiting_list extends Migration
{
    const TABLE_NAME = "event";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->addColumn('event','manage_waiting_list' , $this->integer(1)->defaultValue(0)->after('event_management')->comment('Manage waiting list'));
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn('event','manage_waiting_list');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}