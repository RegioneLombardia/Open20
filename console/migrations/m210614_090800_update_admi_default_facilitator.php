<?php
use yii\db\Migration;

/**
 * Class m201214_120800_update_permission_agid_dataset_admin
 */
class m210614_090800_update_admi_default_facilitator extends Migration
{

    private $table =  '{{%user_profile}}';
    
    public function safeUp()
    {
        $this->update($this->table, ['default_facilitatore' => 1],['id' => 1]);
    }

    public function safeDown()
    {
        $this->update($this->table, ['default_facilitatore' => 0],['id' => 1]);
        return true;
    }
}
