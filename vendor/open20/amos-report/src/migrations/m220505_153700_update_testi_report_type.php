<?php

use yii\db\Migration;

/**
 * Class m220321_101400_add_field_to_middleware2
 */
class m220505_153700_update_testi_report_type extends Migration
{

    public function safeUp()
    {
        $this->update('report_type',['name' => 'contenuti inappropriati','description' => 'contenuti inappropriati'], ['id' => '1']);
        $this->update('report_type',['name' => 'Errori','description' => 'Errori'], ['id' => '2']);
    }

    public function safeDown()
    {
        $this->update('report_type',['name' => 'Inappropriate contents','description' => 'Inappropriate contents'], ['id' => '1']);
        $this->update('report_type',['name' => 'Errors','description' => 'Errors'], ['id' => '2']);

    }
}