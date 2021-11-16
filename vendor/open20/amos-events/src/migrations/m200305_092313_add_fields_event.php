<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200305_092313_add_fields_event extends Migration
{
    const TABLE_EVENT = "event";






    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->addColumn(self::TABLE_EVENT, 'event_location_entrance_id',$this->integer()->after('event_location_id')->comment('Entrance'));
        $this->addForeignKey('fk_event_event_location_entrance_id',self::TABLE_EVENT, 'event_location_entrance_id', 'event_location_entrances','id');
        $this->addColumn(self::TABLE_EVENT, 'video_streaming',$this->string()->after('description')->comment('Video streaming'));
        $this->addColumn(self::TABLE_EVENT, 'conference_call',$this->string()->after('description')->comment('Conference call'));
        $this->addColumn(self::TABLE_EVENT, 'dial_code',$this->string()->after('description')->comment('Dial code'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropForeignKey('fk_event_event_location_entrance_id',self::TABLE_EVENT);
        $this->dropColumn(self::TABLE_EVENT, 'event_location_entrance_id');

        $this->dropColumn(self::TABLE_EVENT, 'video_streaming');
        $this->dropColumn(self::TABLE_EVENT, 'conference_call');
        $this->dropColumn(self::TABLE_EVENT, 'dial_code');
        
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
