<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use yii\db\Migration;
use open20\amos\admin\models\UserProfile;

/**
 * Class m180306_124300_alter_table_user_profile_add_first_access_wizard_steps_accessed
 */
class m180622_124300_alter_table_user_profile_add_field_for_redirect extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(UserProfile::tableName(), 'first_access_login_effectuated', $this->integer()->null()->defaultValue(0)->after('widgets_selected')->comment("First login effectuated"));
        $this->addColumn(UserProfile::tableName(), 'first_access_redirect_url', $this->string()->null()->defaultValue(null)->after('widgets_selected')->comment("First access redirect url"));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(UserProfile::tableName(), 'first_access_login_effectuated');
        $this->dropColumn(UserProfile::tableName(), 'first_access_redirect_url');
    }

}