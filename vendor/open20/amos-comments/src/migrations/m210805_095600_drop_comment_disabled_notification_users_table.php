<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m210805_095600_drop_comment_disabled_notification_users_table
 */
class m210805_095600_drop_comment_disabled_notification_users_table extends \yii\db\Migration
{
    public function safeUp()
    {

        if ($this->db->schema->getTableSchema('comment_disabled_notification_users', true) !== null) {
            $this->dropTable('comment_disabled_notification_users');
        }

    }

    public function safeDown()
    {
        // do nothing
    }
}
