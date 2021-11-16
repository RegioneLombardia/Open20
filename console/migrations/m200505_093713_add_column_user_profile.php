<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200505_093713_add_column_user_profile extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user_profile', 'is_imported', $this->integer()->defaultValue(0)->after('azienda')->comment('Is imported'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn('user_profile', 'is_imported');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
