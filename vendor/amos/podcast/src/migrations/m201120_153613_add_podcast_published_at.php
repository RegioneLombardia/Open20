<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m201120_153613_add_podcast_published_at extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {


        $this->addColumn('podcast', "published_at", $this->dateTime()->comment('Published at')->after('community_id'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn('podcast', "published_at");

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
