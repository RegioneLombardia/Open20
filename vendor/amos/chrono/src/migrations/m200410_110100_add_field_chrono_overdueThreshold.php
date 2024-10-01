<?php

use yii\db\Migration;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200410_110100_add_field_chrono_overdueThreshold extends Migration
{
    const TABLE = "{{%chrono_task}}";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'overdueThreshold',
            $this->integer()->after('active'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE, 'overdueThreshold');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}