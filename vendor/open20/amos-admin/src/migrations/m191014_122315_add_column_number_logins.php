<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfileArea;
use yii\db\Migration;

/**
 * Class m190103_122315_add_column_enable_facilitator_box
 */
class m191014_122315_add_column_number_logins extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user_profile','count_logins', $this->integer()->defaultValue(0)->after('ultimo_accesso')->comment('Number of logins effectuated'));

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('user_profile','count_logins');
        return true;
    }
}
