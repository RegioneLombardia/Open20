<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m190524_173430_alter_table_event
 */
class m191008_100730_add_column_event_invitation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event_invitation', 'is_group', $this->integer(1)->defaultValue(0)->comment('Is group')->after('event_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event_invitation', 'is_group');
    }

}
