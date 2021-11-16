<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200508_124013_add_fields_event_container extends Migration
{
    const TABLE_EVENT_LIST= "event_container";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
     $this->addColumn(self::TABLE_EVENT_LIST, 'publish_on_prl', $this->integer()->defaultValue(0)->after('description'));
     $this->addColumn(self::TABLE_EVENT_LIST, 'slug', $this->string()->after('description'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'publish_on_prl');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'slug');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
