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
 * Class m230302_173800_add_columns_enable_chat_and_enable_bookmarks
 */
class m230302_173800_add_columns_enable_chat_and_enable_bookmarks extends \yii\db\Migration
{
    const COMMUNITY = 'community';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(
            self::COMMUNITY,
            'enable_chat',
            $this->boolean()->defaultValue(true)->after('hide_participants')->comment('Enable chat')
        );
        $this->addColumn(
            self::COMMUNITY,
            'enable_bookmarks',
            $this->boolean()->defaultValue(true)->after('enable_chat')->comment('Enable bookmarks')
        );
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::COMMUNITY, 'enable_chat');
        $this->dropColumn(self::COMMUNITY, 'enable_bookmarks');
        return true;
    }
}