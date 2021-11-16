<?php
use yii\db\Migration;

/**
 * Class m200703_143000_update_tables_for_user_preferences 
 */
class m200703_143000_update_tables_for_user_preferences extends Migration
{

    public function safeUp()
    {

        $this->addColumn('preference_user_target_attribute', 'target_code', $this->string(60)->null()->comment('Target code')->after('validated_phone_flag'));
        $this->addColumn('preference_user_target_attribute', 'user_id', $this->integer(11)->notNull()->comment('User')->after('target_code'));
        $this->addForeignKey('preference_user_target_attribute_user_1', 'preference_user_target_attribute', 'user_id', 'user', 'id');
       
        $this->addColumn('preference_target_channel_mm', 'target_code', $this->string(60)->null()->comment('Target code')->after('preference_sending_frequency_id'));
        $this->addColumn('preference_target_channel_mm', 'user_id', $this->integer(11)->notNull()->comment('User')->after('target_code'));
        $this->addForeignKey('preference_target_channel_mm_user_1', 'preference_target_channel_mm', 'user_id', 'user', 'id');

        $this->dropForeignKey('fk_target_channel_cwh_tag_owner_interest_mm_1', 'preference_target_channel_mm');
        $this->dropColumn('preference_target_channel_mm', 'cwh_tag_owner_interest_mm_id');

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('preference_user_target_attribute', 'target_code');
        $this->dropForeignKey('preference_user_target_attribute_user_1', 'preference_user_target_attribute');
        $this->dropColumn('preference_user_target_attribute', 'user_id');

        $this->dropColumn('preference_target_channel_mm', 'target_code');
        $this->dropForeignKey('preference_target_channel_mm_user_1', 'preference_target_channel_mm');
        $this->dropColumn('preference_target_channel_mm', 'user_id');

        $this->addColumn('preference_target_channel_mm', 'cwh_tag_owner_interest_mm_id', $this->integer(11)->notNull()->comment('FK ad una root, un target scelto dall\'utente'));
        $this->addForeignKey('fk_target_channel_cwh_tag_owner_interest_mm_1', 'preference_target_channel_mm', 'cwh_tag_owner_interest_mm_id', 'cwh_tag_owner_interest_mm', 'id');

        return true;
    }

}
