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
class m180306_124300_alter_table_user_profile_add_first_access_wizard_steps_accessed extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(UserProfile::tableName(), 'first_access_wizard_steps_accessed', $this->text()->null()->defaultValue(null)->after('widgets_selected')->comment("Passi aperti in first access wizard"));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(UserProfile::tableName(), 'first_access_wizard_steps_accessed');
    }

}