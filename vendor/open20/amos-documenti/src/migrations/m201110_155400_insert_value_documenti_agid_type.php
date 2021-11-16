<?php

use yii\db\Migration;

class m201110_155400_insert_value_documenti_agid_type extends Migration
{
    public function safeUp()
    {
        // remove old value 
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        \Yii::$app->db->createCommand()->truncateTable('documenti_agid_type')->execute();
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');



        $this->insert('documenti_agid_type', [
            'name' => 'Bandi di concorso'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Nomine in società ed enti'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Bandi immobiliari'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Bandi per contributi'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Altri bandi e avvisi'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Ordinanze'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Statuto comunale'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Regolamenti'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Pianificazione urbanistica'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Autorizzazioni paesaggistiche'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Pubblicazioni statistiche'
        ]);
    }

    public function safeDown()
    {
       return true;
    }
}