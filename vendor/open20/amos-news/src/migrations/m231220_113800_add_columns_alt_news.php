<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

/**
 * Class m231220_113800_add_columns_alt_news
 */
class m231220_113800_add_columns_alt_news extends \yii\db\Migration
{
    const NEWS = 'news';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(
            self::NEWS,
            'alt',
            $this->string(100)->comment('Alt')
        );
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::NEWS, 'alt');
        return true;
    }
}