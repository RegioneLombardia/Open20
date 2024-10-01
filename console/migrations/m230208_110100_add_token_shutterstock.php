<?php
use yii\db\Migration;

/**
 * Class m230124_171900_serial_number_on_user_profile
 */
class m230208_110100_add_token_shutterstock extends Migration
{

    private $table =  '{{%platform_configs}}';
    
    public function safeUp()
    {
        $this->update($this->table, [
            'value' => 'v2/R0sxVFRueWFZNGxDY1NkR1RHNm52SHhBNkp4THNJdXAvMzc3NDgzMDQzL2N1c3RvbWVyLzQvSFlla0NVNTdlcll0VVJWYnFQOTR4TXlBa1pROTVabXJLSkdZelJNY01JVlFISnNjbFgydnUxS0NaY3MteTYtUnA1MVZEVzFZVlZmYlNNNkNHcVU5UWhfT2JYUE50eWgtNUVlR1FWUUpZRjlTSXdVRjFISFVGWl9PcDUyOTUxNG1NbkdaNThqRjFiU2FUYkV4VF9rUEx3Tk0yRFhGbm44SjVVc2hCcVZYTUhhRko1eGx4bFJIWGRVaWpZX1hSZDNIREkxaXI2X0lpVV84XzF4MlVTZU9lQS9VSDV5WVE1WWxxbnI0bVNmaUxiaVlB'
        ],[
            'module' => 'attachments',
            'key' => 'shutterstock_access_token',
        ]);
    }

    public function safeDown()
    {
        $this->dropColumn($this->table,'serial_number');
    }

}
