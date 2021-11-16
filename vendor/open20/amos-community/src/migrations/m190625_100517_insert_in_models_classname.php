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
class m190625_100517_insert_in_models_classname extends \yii\db\Migration
{
    const CLASSNAME = 'models_classname';


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert(self::CLASSNAME, [
            'classname' => 'open20\amos\community\models\Community',
            'module' => 'community',
            'label' => 'Community'
        ]);
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete(self::CLASSNAME, [
            'classname' => 'open20\amos\community\models\Community',
            'module' => 'community',
            'label' => 'Community'
        ]);
        return true;
    }
}
