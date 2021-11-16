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


class m210114_102200_add_social_sharing_column extends Migration
{

    public function up()
    {
        // addColumn
        $this->addColumn('preference_campain_container', 'social_sharing', $this->integer()->null()->defaultValue(null)->after('content_linkedin_link'));
       
    }

    public function down()
    {
        // dropColumn
        $this->dropColumn('preference_campain_container', 'social_sharing');
    }

}