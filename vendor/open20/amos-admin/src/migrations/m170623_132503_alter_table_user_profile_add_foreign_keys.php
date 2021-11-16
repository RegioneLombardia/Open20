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
 * Class m170623_132503_alter_table_user_profile_add_foreign_keys
 */
class m170623_132503_alter_table_user_profile_add_foreign_keys extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Add column
        $this->addColumn(UserProfile::tableName(), 'presentazione_breve', $this->string(255)->null()->defaultValue(null)->after('sesso'));
        $this->addColumn(UserProfile::tableName(), 'default_facilitatore', $this->boolean()->notNull()->defaultValue('0')->after('facilitatore_id'));
        $this->addColumn(UserProfile::tableName(), 'user_profile_area_id', $this->integer()->null()->defaultValue(null)->after('default_facilitatore'));
        $this->addColumn(UserProfile::tableName(), 'user_profile_area_other', $this->string(255)->null()->defaultValue(null)->after('user_profile_area_id'));
        $this->addColumn(UserProfile::tableName(), 'user_profile_role_id', $this->integer()->null()->defaultValue(null)->after('user_profile_area_other'));
        $this->addColumn(UserProfile::tableName(), 'user_profile_role_other', $this->string(255)->null()->defaultValue(null)->after('user_profile_role_id'));
        $this->addColumn(UserProfile::tableName(), 'user_profile_age_group_id', $this->integer()->null()->defaultValue(null)->after('user_profile_role_other'));
        $this->addColumn(UserProfile::tableName(), 'prevalent_partnership_id', $this->integer()->null()->defaultValue(null)->after('user_profile_age_group_id'));
        
        // Add foreign keys
        $this->addForeignKey('fk_user_profile_area_user_profile', UserProfile::tableName(), 'user_profile_area_id', '{{%user_profile_area}}', 'id');
        $this->addForeignKey('fk_user_profile_role_user_profile', UserProfile::tableName(), 'user_profile_role_id', '{{%user_profile_role}}', 'id');
        $this->addForeignKey('fk_user_profile_age_group_user_profile', UserProfile::tableName(), 'user_profile_age_group_id', '{{%user_profile_age_group}}', 'id');
        if ($this->db->schema->getTableSchema('{{%aziende}}', true) !== null) {
            $this->addForeignKey('fk_user_profile_prevalent_partnership', UserProfile::tableName(), 'prevalent_partnership_id', '{{%aziende}}', 'id');
        }
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // Remove foreign keys
        $this->dropForeignKey('fk_user_profile_area_user_profile', UserProfile::tableName());
        $this->dropForeignKey('fk_user_profile_role_user_profile', UserProfile::tableName());
        $this->dropForeignKey('fk_user_profile_age_group_user_profile', UserProfile::tableName());
        if ($this->db->schema->getTableSchema('{{%aziende}}', true) !== null) {
            $this->dropForeignKey('fk_user_profile_prevalent_partnership', UserProfile::tableName());
        }
        
        // Remove column
        $this->dropColumn(UserProfile::tableName(), 'presentazione_breve');
        $this->dropColumn(UserProfile::tableName(), 'default_facilitatore');
        $this->dropColumn(UserProfile::tableName(), 'user_profile_area_id');
        $this->dropColumn(UserProfile::tableName(), 'user_profile_area_other');
        $this->dropColumn(UserProfile::tableName(), 'user_profile_role_id');
        $this->dropColumn(UserProfile::tableName(), 'user_profile_role_other');
        $this->dropColumn(UserProfile::tableName(), 'user_profile_age_group_id');
        $this->dropColumn(UserProfile::tableName(), 'prevalent_partnership_id');
    }
}
