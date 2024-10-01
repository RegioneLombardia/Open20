<?php
use yii\db\Migration;
use open20\agid\person\models\AgidPersonContentType;


/**
* Class m210712_123000_add_agid_person_content_type_field*/
class m210712_123000_add_agid_person_content_type_field extends Migration
{
    public function safeUp() {
        $this->addColumn(AgidPersonContentType::tableName(),'content_type_icon', $this->string(255)->null()->defaultValue(null)->after('description'));
        return true;
    }

    public function safeDown() {
        $this->dropColumn(AgidPersonContentType::tableName(),'content_type_icon');
        return true;
    }
}
