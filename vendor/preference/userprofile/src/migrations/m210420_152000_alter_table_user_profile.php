<?php

use yii\db\Migration;
use preference\userprofile\models\Tag;

/**
 * Class m210420_152000_alter_table_user_profile
 */
class m210420_152000_alter_table_user_profile extends Migration
{

    public function safeUp()
    {
        $this->alterColumn('user_profile', 'codice_fiscale', $this->string(255));
        return true;
    }

    public function safeDown()
    {
        $this->alterColumn('user_profile', 'codice_fiscale', $this->string(16));
        return true;
    }

}
