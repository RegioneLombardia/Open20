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
 * Class m171015_164853_update_foreign_keys
 */
class m171015_164853_update_foreign_keys extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema('{{%aziende}}', true) !== null) {
            $this->dropForeignKey('fk_user_profile_prevalent_partnership', UserProfile::tableName());
        }

        if ($this->db->schema->getTableSchema('{{%organizations}}', true) !== null) {
            $this->addForeignKey('fk_user_profile_prevalent_partnership', UserProfile::tableName(),
                'prevalent_partnership_id', '{{%organizations}}', 'id');
        }


        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if ($this->db->schema->getTableSchema('{{%organizations}}', true) !== null) {
            $this->dropForeignKey('fk_user_profile_prevalent_partnership', UserProfile::tableName());
        }
        return true;
    }


}
