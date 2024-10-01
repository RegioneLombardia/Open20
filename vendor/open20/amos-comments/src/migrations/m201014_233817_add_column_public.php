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
 * Class m201014_233817_add_column_public
 *
 */
class m201014_233817_add_column_public extends \yii\db\Migration
{
    const COMMENT = 'comment';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::COMMENT, 'public', $this->integer()->defaultValue(1)->after('context_id'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::COMMENT, 'public');
    }
}