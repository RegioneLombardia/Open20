<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */

use yii\db\Migration;
use backend\modules\campains\models\PreferenceCampainContainerType;


class m200904_102500_insert_data_in_to_preference_campain_container_type_table extends Migration
{

    public function safeUp()
    {

        $this->insert('preference_campain_container_type', [
            'name' => 'Contenuto',
            'description' => '',
        ]);

        $this->insert('preference_campain_container_type', [
            'name' => 'Contenuto Esterno',
            'description' => '',
        ]);

        $this->insert('preference_campain_container_type', [
            'name' => 'Separatore',
            'description' => '',
        ]);
    }


    public function safeDown()
    {
        // remove all preference campain container type
        PreferenceCampainContainerType::deleteAll(['deleted_at' => null]);
    }

}



