<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

class m230428_154200_alter_table_privacy_history_add_token_add_notificationlevel extends \yii\db\Migration
{
    const TABLE_NAME = 'privacy_history';
    const COLUMN1 = 'token';
    const COLUMN2 = 'notification_level';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, self::COLUMN1, $this->string(32)->notNull()->comment('Token per disiscrizione newsletter'));
        $this->addColumn(self::TABLE_NAME, self::COLUMN2, $this->integer(11)->notNull()->comment('livello di notifica inviata'));
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, self::COLUMN2);
        $this->dropColumn(self::TABLE_NAME, self::COLUMN1);
        return true;
    }
}
