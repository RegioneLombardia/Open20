<?php

use yii\db\Migration;

class m201116_150200_remove_value_from_content_type_document_type extends Migration
{
    public function safeUp()
    {
        // remove old value from documenti_agid_content_type
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        \Yii::$app->db->createCommand()->truncateTable('documenti_agid_content_type')->execute();
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        // remove old value from documenti_agid_type
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        \Yii::$app->db->createCommand()->truncateTable('documenti_agid_type')->execute();
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
        
    }

    public function safeDown()
    {
       return true;
    }
}