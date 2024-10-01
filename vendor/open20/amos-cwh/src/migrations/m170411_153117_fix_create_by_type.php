<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */
use yii\db\Migration;

/**
 * Class m170411_153117_fix_create_by_type
 */
class m170411_153117_fix_create_by_type extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('cwh_config_contents','created_by',$this->integer()->null());
        $this->alterColumn('cwh_config_contents','updated_by',$this->integer()->null());
        $this->alterColumn('cwh_config_contents','deleted_by',$this->integer()->null());
        return true;
    }

    public function safeDown()
    {
        $this->alterColumn('cwh_config_contents','created_by',$this->dateTime()->null());
        $this->alterColumn('cwh_config_contents','updated_by',$this->dateTime()->null());
        $this->alterColumn('cwh_config_contents','deleted_by',$this->dateTime()->null());

        return true;
    }
}
