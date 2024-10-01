<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m201028_134013_add_podcast_duration extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {


        $this->addColumn('podcast_episode', "duration", $this->string()->defaultValue(null)->comment('Duration')->after('abstract'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn('podcast_episode', "duration");

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
