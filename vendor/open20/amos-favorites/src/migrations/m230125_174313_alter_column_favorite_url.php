<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m230125_174313_alter_column_favorite_url extends Migration
{
    const TABLE = "favorite";




    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->alterColumn(self::TABLE,'url', $this->text()->defaultValue(null)->comment('Url'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->alterColumn(self::TABLE,'url', $this->string()->defaultValue(null)->comment('Url'));
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
