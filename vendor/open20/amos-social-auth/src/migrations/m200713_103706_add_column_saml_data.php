<?php

use yii\db\Migration;

class m200713_103706_add_column_saml_data extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%social_idm_user}}', 'rawData', \yii\db\Schema::TYPE_TEXT);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%social_idm_user}}', 'rawData');

        return true;
    }
}
