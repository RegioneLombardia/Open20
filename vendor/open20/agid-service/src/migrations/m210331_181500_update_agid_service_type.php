<?php
use yii\db\Migration;
use open20\agid\service\models\AgidServiceType;

/**
 * Class m2100331_181500_update_agid_service_type */
class m210331_181500_update_agid_service_type extends Migration
{
    public function safeUp()
    {

        $agid_service_type = AgidServiceType::find()->where(['name' => 'Cultura e tempo libero'])->one();
    
        // set new name
        $agid_service_type->name = "Cultura, sport e tempo libero";
        $agid_service_type->save(false);

    }
    
    public function safeDown()
    {
        return true;
    }

}
