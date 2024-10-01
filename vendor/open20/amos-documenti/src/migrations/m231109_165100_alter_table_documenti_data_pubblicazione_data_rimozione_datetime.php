<?php
use yii\db\Migration;

/**
 * Class m231109_165100_alter_table_documenti_data_pubblicazione_data_rimozione_datetime
 */
class m231109_165100_alter_table_documenti_data_pubblicazione_data_rimozione_datetime extends Migration
{

    private $table =  '{{%documenti}}';

    public function safeUp()
    {
        $this->alterColumn($this->table, "data_pubblicazione", \yii\db\Schema::TYPE_DATETIME);
        $this->alterColumn($this->table, "data_rimozione", \yii\db\Schema::TYPE_DATETIME);
    }

    public function safeDown()
    {
        $this->alterColumn($this->table, "data_pubblicazione", \yii\db\Schema::TYPE_DATE);
        $this->alterColumn($this->table, "data_rimozione", \yii\db\Schema::TYPE_DATE);
    }

}