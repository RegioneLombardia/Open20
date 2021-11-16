<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200729_154613_add_location_streaming extends Migration
{
    const TABLE_LOCATION= "event_location";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_LOCATION, 'is_location_streaming', $this->integer(0)->defaultValue(0)->after('event_place_id'));
        $this->insert('event_location',  [
            'name' => 'Evento streaming',
            'description' => 'Evento streaming',
            'is_location_streaming' => true,
            'hidden' => false
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_LOCATION, 'is_location_streaming');
        $this->delete('event_location',  [
            'name' => 'Evento streaming',
            'description' => 'Evento streaming',
            'is_location_streaming' => true,
            'hidden' => false
        ]);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
