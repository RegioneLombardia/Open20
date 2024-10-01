<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m201019_154013_add_podcast_community_id extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {


        $this->addColumn('podcast', "community_id", $this->integer()->defaultValue(null)->comment('Community')->after('podcast_category_id'));
        $this->addForeignKey('fk_podcast_community_id1', 'podcast', "community_id", 'community', 'id');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropForeignKey('fk_podcast_community_id1', 'podcast');
        $this->addColumn('podcast', "community_id", $this->integer()->defaultValue(null)->comment('Community')->after('podcast_category_id'));

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
