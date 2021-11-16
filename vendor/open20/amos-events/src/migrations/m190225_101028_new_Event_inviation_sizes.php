<?php

use yii\db\Migration;

/**
 * Class m190225_101028_new_Event_inviation_sizes
 */
class m190225_101028_new_Event_inviation_sizes extends Migration
{
    public static $tableName = '{{%event_invitation}}';
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
           $this->alterColumn(self::$tableName, 'name', $this->string(50));
           $this->alterColumn(self::$tableName, 'surname', $this->string(50));
           
           return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn(self::$tableName, 'name', $this->string(16));
        $this->alterColumn(self::$tableName, 'surname', $this->string(16));

        return true;
    }
}
