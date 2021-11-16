<?php
use yii\db\Migration;

/**
 * Class m210115_164100_update_template */
class m210115_164100_update_template extends Migration
{
    public function safeUp()
    {
        $this->update('preference_mailup_template', ['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => 1], ['id' => 1]);
        $this->update('preference_mailup_template', ['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => 1], ['id' => 2]);
    }
    
    public function safeDown()
    {
        $this->update('preference_mailup_template', ['deleted_at' => null, 'deleted_by' => null], ['id' => 1]);
        $this->update('preference_mailup_template', ['deleted_at' => null, 'deleted_by' => null], ['id' => 2]);
    }

}
