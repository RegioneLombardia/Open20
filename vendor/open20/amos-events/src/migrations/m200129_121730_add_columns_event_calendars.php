<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m190524_173430_alter_table_event
 */
class m200129_121730_add_columns_event_calendars extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event_calendars', 'partner_user_id', $this->integer()->comment('Partner')->after('description'));
        $this->addColumn('event_calendars', 'group', $this->string()->comment('Group')->after('description'));
        $this->addColumn('event_calendars', 'ecosystem', $this->string()->comment('Ecosystem')->after('description'));
        $this->addColumn('event_calendars', 'short_description', $this->text()->comment('Short description')->after('description'));
        
        $this->addForeignKey('fk_event_calendars_partner_user_id1', 'event_calendars', 'partner_user_id', 'user', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropForeignKey('fk_event_calendars_partner_user_id1', 'event_calendars');
        $this->dropColumn('event_calendars', 'partner_user_id');
        $this->dropColumn('event_calendars', 'group');
        $this->dropColumn('event_calendars', 'ecosystem');
        $this->dropColumn('event_calendars', 'short_description');
        $this->execute('SET FOREIGN_KEY_CHECKS=1');

    }

}
