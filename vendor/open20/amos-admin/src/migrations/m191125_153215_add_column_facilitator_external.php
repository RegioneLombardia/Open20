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
class m191125_153215_add_column_facilitator_external extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user_profile','external_facilitator_id', $this->integer()->defaultValue(null)->after('facilitatore_id')->comment('External facilitator'));

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('user_profile','external_facilitator_id');
        return true;
    }
}
