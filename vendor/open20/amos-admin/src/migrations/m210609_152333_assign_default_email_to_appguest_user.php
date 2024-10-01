<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\user\User;
use yii\db\Migration;

/**
 * Class m210609_152333_assign_default_email_to_appguest_user
 */
class m210609_152333_assign_default_email_to_appguest_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update(User::tableName(), ['email' => 'appguest@appguest.com'], ['username' => 'appguest']);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m210609_152333_assign_default_email_to_appguest_user cannot be reverted.\n";
        return false;
    }
}
