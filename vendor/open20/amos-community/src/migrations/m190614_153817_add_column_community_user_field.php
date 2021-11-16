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
class m190614_153817_add_column_community_user_field extends \yii\db\Migration
{
    const COMMUNITY = 'community_user_field';


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::COMMUNITY, 'validator_classname', $this->string()->after('required'));
        $this->addColumn(self::COMMUNITY, 'unique', $this->integer(1)->defaultValue(0)->after('required'));
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::COMMUNITY, 'validator_classname');
        $this->dropColumn(self::COMMUNITY, 'unique');
        return true;
    }
}
