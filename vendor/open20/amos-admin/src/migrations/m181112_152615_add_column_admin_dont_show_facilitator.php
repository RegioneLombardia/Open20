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
 * Class m181012_162615_add_user_profile_area_field_1
 */
class m181112_152615_add_column_admin_dont_show_facilitator extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user_profile','dont_show_facilitator', $this->integer(1)->defaultValue(0)->after('default_facilitatore')->comment('Dont show facilitator in lists'));

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('user_profile','dont_show_facilitator');
        return true;
    }
}
