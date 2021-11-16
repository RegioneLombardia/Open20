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


class m200921_173000_add_column_to_template extends Migration
{

    public function up()
    {
        // addColumn
        $this->addColumn('preference_mailup_template', 'content_no_image', $this->text()->null()->defaultValue(null)->after('content_center_image'));
       
    }

    public function down()
    {
        // dropColumn
        $this->dropColumn('preference_mailup_template', 'content_no_image');
    }

}