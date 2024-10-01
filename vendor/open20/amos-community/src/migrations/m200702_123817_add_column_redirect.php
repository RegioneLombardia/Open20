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
 * Class m200702_123817_add_column_redirect
 *
 */
class m200702_123817_add_column_redirect extends \yii\db\Migration
{
    const COMMUNITY = 'community';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::COMMUNITY, 'redirect_url', $this->text()->null()->after('contents_visibility'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::COMMUNITY, 'redirect_url');
        return true;
    }
}