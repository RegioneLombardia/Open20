<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m201201_171813_add_apple_reg extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('event_landing', 'apple_reg', $this->integer(1)->after('goolge_reg'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn('event_landing', 'apple_reg');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
