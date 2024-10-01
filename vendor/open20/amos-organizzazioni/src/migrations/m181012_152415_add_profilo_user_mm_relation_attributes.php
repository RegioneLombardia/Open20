<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\ProfiloUserMm;
use yii\db\Migration;

/**
 * Class m181012_152415_add_profilo_user_mm_relation_attributes
 */
class m181012_152415_add_profilo_user_mm_relation_attributes extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = ProfiloUserMm::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'user_profile_area_id', $this->integer()->notNull()->defaultValue(null)->after('role')->comment('User Profile Area Id'));
        $this->addColumn($this->tableName, 'user_profile_role_id', $this->integer()->notNull()->defaultValue(null)->after('user_profile_area_id')->comment('User Profile Role Id'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'user_profile_area_id');
        $this->dropColumn($this->tableName, 'user_profile_role_id');
        return true;
    }
}
