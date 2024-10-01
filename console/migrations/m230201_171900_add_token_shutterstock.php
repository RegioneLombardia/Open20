<?php
use yii\db\Migration;

/**
 * Class m230124_171900_serial_number_on_user_profile
 */
class m230201_171900_add_token_shutterstock extends Migration
{

    private $table =  '{{%platform_configs}}';
    
    public function safeUp()
    {
        $this->insert($this->table, [
            'module' => 'attachments',
            'key' => 'shutterstock_access_token',
            'value' => 'v2/clNmRVdDbmkwSFBpdlBYRzg5UVk4eXVRVVZuWjFjdkQvMTc5OTk3MzA0L2N1c3RvbWVyLzQvYVg4VEVOSjExaFZfNlRUd0NrQmY2U3FqSkJSTVI0NGJjcldIUG9fOWFGR2ZWd21zXzZVSjJBN0NwMXJFbUxNTU9EWkNRZkxkN1ZCbWM1NmdDekw2dTlPUTRfQW5jV3FnN3BhOVE5SldPZDRTRWNSU3hmMWhSbE83N2U0NnlQSVhnQTZCd3VZZXRzWGR0RWdQRlFVTUotSzkxTGdjTEJFUFF2WnFxcy1aX0hZWHRWaWJvaHQ3V2JIRGdncmhCOGZjUXVBNEhnVHpoNTc0UkxRMFpGS2l0YW9KVE9xNjdES1d6ZENvWkFWZHZqVS9ZV3B1X0tGV3p1WnVia1FhY0NDMm9B'
        ]);
    }

    public function safeDown()
    {
        $this->dropColumn($this->table,'serial_number');
    }

}
