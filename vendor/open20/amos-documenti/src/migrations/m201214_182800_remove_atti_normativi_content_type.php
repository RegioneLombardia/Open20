<?php

use open20\amos\documenti\models\DocumentiAgidType;
use yii\db\Migration;


class m201214_182800_remove_atti_normativi_content_type extends Migration
{
    public function safeUp()
    {
        $doct = DocumentiAgidType::findOne(['name' => 'Atti normativi', 'agid_document_content_type_id' => 6]);
        if (!is_null($doct)) {
            $doct->delete();
        }
    }
    
    public function safeDown()
    {
       return true;
    }
}
