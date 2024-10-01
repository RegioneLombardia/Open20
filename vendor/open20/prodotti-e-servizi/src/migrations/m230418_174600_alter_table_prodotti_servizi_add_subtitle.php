<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */


class m230418_174600_alter_table_prodotti_servizi_add_subtitle extends \yii\db\Migration
{
    const TABLE_NAME = 'prodotti_servizi';
    const COLUMN = 'subtitle';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, self::COLUMN, $this->string()->notNull()->after('title'));
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, self::COLUMN);
        return true;
    }
}
