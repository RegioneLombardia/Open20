<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200403_090813_add_fields_event_landing2 extends Migration
{
    const TABLE_EVENT_LANDING = "event_landing";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
     $this->addColumn(self::TABLE_EVENT_LANDING, 'nav_id_tks_page', $this->integer()->after('luya_page_id'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'nav_id_wating_page', $this->integer()->after('nav_id_tks_page'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'nav_id_already_present_page', $this->integer()->after('nav_id_wating_page'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'nav_id_tks_page');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'nav_id_wating_page');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'nav_id_already_present_page');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
