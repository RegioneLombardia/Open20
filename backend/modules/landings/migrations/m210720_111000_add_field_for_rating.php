<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m210720_111000_add_field_for_rating`.
 */
class m210720_111000_add_field_for_rating extends Migration
{
    const TABLE = "preference_landing";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'rating_flag', $this->boolean()->null()->defaultValue(null)->after('sondaggi_id'));
        $this->addColumn(self::TABLE, 'rating_description', $this->text()->defaultValue(null)->after('rating_flag'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'rating_flag');
        $this->dropColumn(self::TABLE, 'rating_description');
    }
}
