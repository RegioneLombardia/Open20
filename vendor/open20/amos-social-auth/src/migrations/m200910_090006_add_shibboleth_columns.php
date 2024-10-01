<?php

use yii\db\Migration;

class m200910_090006_add_shibboleth_columns extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%social_idm_user}}', 'accessMethod', \yii\db\Schema::TYPE_STRING);
        $this->addColumn('{{%social_idm_user}}', 'accessLevel', \yii\db\Schema::TYPE_STRING);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%social_idm_user}}', 'accessMethod');
        $this->dropColumn('{{%social_idm_user}}', 'accessLevel');

        return true;
    }
}
