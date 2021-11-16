<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\db\Migration;
use yii\rbac\Permission;

/**
 * Class m200710_170000_add_validation_tokens_to_user_target_attribute */
class m200710_170000_add_validation_tokens_to_user_target_attribute extends Migration
{

    public function safeUp()
    {
        $this->addColumn('preference_user_target_attribute', 'email_validation_token', $this->string(255)->null()->comment('email token')->after('email'));
        $this->addColumn('preference_user_target_attribute', 'phone_validation_token', $this->string(255)->null()->comment('phone token')->after('phone'));
    }
    
    public function safeDown()
    {
        $this->dropColumn('preference_user_target_attribute', 'email_validation_token');
        $this->dropColumn('preference_user_target_attribute', 'phone_validation_token');
    }

}
