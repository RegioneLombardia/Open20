<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

use yii\db\Migration;

class m170331_135813_change_name_column_lenght extends Migration
{
    public function up()
    {
        $this->alterColumn('tag','nome','varchar(255)');

        return true;
    }

    public function down()
    {
        echo "m170331_135813_change_name_column_lenght cannot be reverted.\n";

        return false;
    }
    
}
