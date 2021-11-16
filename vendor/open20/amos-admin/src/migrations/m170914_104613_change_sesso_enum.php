<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use yii\db\Migration;
use open20\amos\admin\models\UserProfile;

class m170914_104613_change_sesso_enum extends Migration
{

    private $table = null;


    public function __construct()
    {
        $this->table = UserProfile::tableName();
        parent::__construct();
    }


    public function safeUp()
    {
        $this->alterColumn($this->table,'sesso', "ENUM('Maschio','Femmina','') DEFAULT NULL COMMENT 'Sesso'");
        return true;
    }

    public function safeDown()
    {

        $this->alterColumn($this->table,'sesso', "ENUM('Maschio','Femmina') DEFAULT NULL COMMENT 'Sesso'");
        return true;
    }


}
