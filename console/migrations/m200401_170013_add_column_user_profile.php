<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200401_170013_add_column_user_profile extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user_profile', 'azienda', $this->string()->after('telefono')->comment('Azienda'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn('user_profile', 'azienda');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
