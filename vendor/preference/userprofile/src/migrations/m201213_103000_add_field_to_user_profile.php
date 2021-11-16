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
class m201213_103000_add_field_to_user_profile extends Migration
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
        $this->addColumn($this->tableName, 'pc_request_delete_date', $this->dateTime()->null()->after('pc_structure_id')->comment('Data e ora di richiesta di eliminazione del profilo'));

        $this->execute("
        UPDATE `user_profile` inner join user on user.id = user_profile.user_id
        SET user_profile.pc_request_delete_date = user_profile.updated_at
        WHERE user.status = 50;
        ");

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'pc_request_delete_date');
        return true;
    }
}
