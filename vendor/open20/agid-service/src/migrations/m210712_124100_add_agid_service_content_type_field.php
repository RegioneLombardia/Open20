<?php

use yii\db\Migration;
use open20\agid\service\models\AgidServiceContentType;

/**
 * this migration remove status of 
 * 
 * m210712_124100_add_agid_service_content_type_field
 * 
 * 
 */
class m210712_124100_add_agid_service_content_type_field extends Migration {

    public function safeUp() {
        $this->addColumn(AgidServiceContentType::tableName(),'content_type_icon', $this->string(255)->null()->defaultValue(null)->after('description'));
        return true;
    }

    public function safeDown() {
        $this->dropColumn(AgidServiceContentType::tableName(),'content_type_icon');
        return true;
    }

}