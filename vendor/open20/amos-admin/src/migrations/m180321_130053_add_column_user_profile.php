<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use yii\db\Migration;

/**
 * Class m171015_164853_update_foreign_keys
 */
class m180321_130053_add_column_user_profile extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user_profile', 'notify_from_editorial_staff', $this->integer(1)->defaultValue(1)->after('user_id'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('user_profile', 'notify_from_editorial_staff');

        return true;
    }


}
