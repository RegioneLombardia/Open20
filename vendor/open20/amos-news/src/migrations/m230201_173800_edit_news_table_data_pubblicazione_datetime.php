<?php
use yii\db\Migration;

/**
 * Class m230201_173800_edit_news_table_data_pubblicazione_datetime
 */
class m230201_173800_edit_news_table_data_pubblicazione_datetime extends Migration
{

    private $table =  '{{%news}}';

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