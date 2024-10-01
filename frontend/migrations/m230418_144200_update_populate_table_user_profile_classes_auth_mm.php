<?php

use yii\db\Migration;
/**
 * Class m230418_144200_update_populate_table_user_profile_classes_auth_mm
 */
class m230418_144200_update_populate_table_user_profile_classes_auth_mm extends Migration
{
    private $table = '{{%user_profile_classes_auth_mm}}';

    public function safeUp()
    {
        $this->update($this->table, ['item_id' => 'AMMINISTRATORE_NEWS'], ['id' => 8]);
    }

    public function safeDown()
    {
        $this->update($this->table, ['item_id' => 'CREATORE_NEWS'], ['id' => 8]);
    }
}