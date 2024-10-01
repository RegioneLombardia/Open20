<?php
use yii\db\Migration;

/**
 * Class m230124_171900_serial_number_on_user_profile
 */
class m230124_171900_serial_number_on_user_profile extends Migration
{

    private $table =  '{{%user_profile}}';
    
    public function safeUp()
    {
        $this->addColumn($this->table,'serial_number',$this->string(100)->comment('matricola')->after('codice_fiscale'));

        $this->update($this->table, ['start_service_date' => '2023-01-01 00:00'], ['id' => [1]]);
        $this->update($this->table, ['serial_number' => '000000000001'], ['id' => [1]]);
        $this->update($this->table, ['serial_number' => 'WUBWY6N5DU94'], ['id' => [17]]);
        $this->update($this->table, ['serial_number' => '7KEXYZ66547C'], ['id' => [21]]);
        $this->update($this->table, ['serial_number' => 'T9LU4CECFU2Z'], ['id' => [18]]);
        $this->update($this->table, ['serial_number' => 'JXYR3MW43CAM'], ['id' => [20]]);
        $this->update($this->table, ['serial_number' => 'Y3PME59H7T7H'], ['id' => [19]]);
        $this->update($this->table, ['serial_number' => 'T9LU4CECFU2Z'], ['id' => [22]]);
    }

    public function safeDown()
    {
        $this->dropColumn($this->table,'serial_number');
    }

}
