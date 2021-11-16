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
class m181024_132615_add_column_token_group1 extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('token_group','string_code', $this->string()->after('id')->comment('Code'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('token_group','string_code');
        return true;
    }
}
