<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m200204_182830_add_columns_event_3
 */
class m200204_182830_add_columns_event_3 extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'use_token', $this->integer()->after('thank_you_page_view'));
        $this->addColumn('event', 'token_group_string_code', $this->string()->after('use_token'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'use_token');
        $this->dropColumn('event', 'token_group_string_code');
    }
}