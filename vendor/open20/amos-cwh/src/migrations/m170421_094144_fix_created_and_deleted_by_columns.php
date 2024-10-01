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
 * Class m170421_094144_fix_created_and_deleted_by_columns
 */
class m170421_094144_fix_created_and_deleted_by_columns extends Migration
{

    private $tables = [

    ];
    public function safeUp()
    {
        $this->alterColumn('cwh_auth_assignment','created_by',$this->integer()->null());
        $this->alterColumn('cwh_auth_assignment','updated_by',$this->integer()->null());
        $this->alterColumn('cwh_auth_assignment','deleted_by',$this->integer()->null());
        return true;
    }

    public function safeDown()
    {
        $this->alterColumn('cwh_auth_assignment','created_by',$this->dateTime()->null());
        $this->alterColumn('cwh_auth_assignment','updated_by',$this->dateTime()->null());
        $this->alterColumn('cwh_auth_assignment','deleted_by',$this->dateTime()->null());
        return true;
    }

}
