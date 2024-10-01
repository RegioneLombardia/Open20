<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m200714_175430_add_columns_event
 */
class m200714_175430_add_columns_event extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'ics_libero',
            $this->integer()->defaultValue(0)->comment('Download libero ICS')->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'ics_libero');
    }
}