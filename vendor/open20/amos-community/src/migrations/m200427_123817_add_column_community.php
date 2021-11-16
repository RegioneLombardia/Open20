<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

/**
 * Class m170301_090817_alter_table_community_add_flags
 *
 * Create flag fields:
 * 'validated_once' : true if community has been validated at least one time
 * 'visible_on_edit': true if community must still be visible if is in editing status and validated_once is true
 */
class m200427_123817_add_column_community extends \yii\db\Migration
{
    const COMMUNITY = 'community';


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::COMMUNITY, 'for_all_user', $this->integer()->defaultValue(0)->after('contents_visibility'));
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::COMMUNITY, 'for_all_user');
        return true;
    }
}
