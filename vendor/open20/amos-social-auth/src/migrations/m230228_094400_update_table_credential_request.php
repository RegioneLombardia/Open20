<?php

use yii\db\Migration;

class m230228_094400_update_table_credential_request extends Migration
{
    public function safeUp(){
        $this->addColumn('credential_request', 'email', $this->text());
    }

    public function safeDown(){
        $this->dropColumn('credential_request', 'email');
    }
}
