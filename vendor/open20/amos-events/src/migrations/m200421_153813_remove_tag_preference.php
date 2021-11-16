<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200421_153813_remove_tag_preference extends Migration
{
    const TABLE_EVENT_LANDING = "tag";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
     $this->delete('tag',  ['codice' => 'preference_19']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
