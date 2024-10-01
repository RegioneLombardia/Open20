<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

class m230905_174500_alter_attribute_description_prodotti_servizi_accordion extends \yii\db\Migration
{
    const TABLE_NAME = 'prodotti_servizi_accordion';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn(self::TABLE_NAME, 'description', 'text not null');
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn(self::TABLE_NAME, 'description', 'string not null');
        return true;
    }
}