<?php

use yii\db\Migration;
/**
 * Class m230331_153900_populate_table_user_profile_classes_auth_mm_role_utente
 */
class m230331_153900_populate_table_user_profile_classes_auth_mm_role_utente extends Migration
{
    private $table = '{{%user_profile_classes_auth_mm}}';

    public function safeUp()
    {
        // user_profile_classes_auth_mm
        $this->insert($this->table,
            [
                'user_profile_classes_id' => 3,
                'item_id' => 'STANDARD_USER',
            ]
        );
    }

    public function safeDown()
    {
        $this->execute("SET foreign_key_checks = 0;");
        $this->delete($this->table, ['item_id' => 'STANDARD_USER']);
        $this->execute("SET foreign_key_checks = 1;");
    }
}