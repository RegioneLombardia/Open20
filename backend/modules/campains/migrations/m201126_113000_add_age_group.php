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


class m201126_113000_add_age_group extends Migration
{

    public function up()
    {
        $this->insert('user_profile_age_group', ['id' => 7, 'age_group' => '<18', 'enabled' => 1, 'order' => 5]);
    }

    public function down()
    {
        $this->delete('user_profile_age_group', ['id' => 7]);
    }

}