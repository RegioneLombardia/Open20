<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m200204_182730_add_columns_event_3
 */
class m200204_182730_add_columns_event_3 extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'thank_you_page_already_registered_view',
            $this->string()->comment('Thank you page custom in caso di utente già registrato')->after('thank_you_page_view'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'thank_you_page_already_registered_view');
    }
}