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
 * Class m200827_115315_add_user_profile_structure_id_field
 */
class m201218_150000_add_field_to_user_profile extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = UserProfile::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'preference_origin_system_id', $this->integer(11)->null()->after('pc_request_delete_date')->comment('Sistema di origine'));
        $this->addForeignKey('fk_user_profile_preference_origin_system_1', $this->tableName, 'preference_origin_system_id', 'preference_origin_system', 'id');

        $this->execute("
            UPDATE user_profile inner join auth_assignment on auth_assignment.user_id = user_profile.user_id
            SET user_profile.preference_origin_system_id = 1
            WHERE auth_assignment.item_name = 'PC_REGISTERD_USER';
        ");

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_profile_preference_origin_system_1', $this->tableName);
        $this->dropColumn($this->tableName, 'preference_origin_system_id');
        return true;
    }
}
