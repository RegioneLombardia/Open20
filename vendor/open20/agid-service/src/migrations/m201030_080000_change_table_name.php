<?php

use yii\db\Schema;

class m201030_080000_change_table_name extends \yii\db\Migration
{
    public function safeUp()

    {
        $this->dropForeignKey('fk_agid_content_type_service','agid_service');
        $this->renameTable("agid_content_type_service", "agid_service_content_type");
        $this->addForeignKey('fk_service_agid_content_type', 'agid_service', 'agid_content_type_service_id', 'agid_service_content_type', 'id');
        }
    public function safeDown()
    {
        echo "m201030_080000_change_table_name cannot be reverted.\n";

        return false;
    }
}