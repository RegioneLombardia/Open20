<?php
use yii\db\Migration;

/**
 * Class m230123_180000_service_dates_on_user_profile
 */
class m230123_180000_service_dates_on_user_profile extends Migration
{

    private $table =  '{{%user_profile}}';
    
    public function safeUp()
    {
        $this->addColumn($this->table,'start_service_date',$this->dateTime()->comment('inizio data di servizio')->after('ultimo_accesso'));
        $this->addColumn($this->table,'end_service_date',$this->dateTime()->comment('fine data di servizio')->after('start_service_date'));

        $this->update($this->table, ['start_service_date' => '2023-01-01 00:00'], ['id' => [17,21,18,20,19,22]]);
        $this->update($this->table, ['end_service_date' => '2023-12-31 00:00'], ['id' => [17,21,18,20,19,22]]);
    }

    public function safeDown()
    {
        $this->dropColumn($this->table,'start_service_date');
        $this->dropColumn($this->table,'end_service_date');

    }

}
