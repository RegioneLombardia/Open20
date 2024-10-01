<?php

use yii\db\Migration;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200410_110000_add_field_chrono_active extends Migration
{
    const TABLE = "{{%chrono_task}}";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'active',
            $this->integer()->after('status'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE, 'active');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}