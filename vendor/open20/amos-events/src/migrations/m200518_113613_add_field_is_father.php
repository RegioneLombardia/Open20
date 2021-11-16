<?php

use yii\db\Migration;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200518_113613_add_field_is_father extends Migration
{
    const TABLE_EVENT_LIST= "event";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_EVENT_LIST, 'is_father', $this->integer(1)->defaultValue(0)->after('event_id'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'is_father');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
