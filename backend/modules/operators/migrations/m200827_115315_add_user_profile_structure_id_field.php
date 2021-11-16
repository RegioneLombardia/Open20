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
class m200827_115315_add_user_profile_structure_id_field extends Migration
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
        $this->addColumn($this->tableName, 'pc_structure_id', $this->integer()->null()->after('notify_from_editorial_staff')->comment(''));
        $this->addForeignKey('fk_user_profile_pc_structure', $this->tableName, 'pc_structure_id', 'preference_structure', 'id');

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'pc_structure_id');
        $this->dropForeignKey('fk_user_profile_pc_structure', $this->tableName);
        return true;
    }
}
