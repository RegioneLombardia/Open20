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
class m221202_165717_fix_record_models_classname extends \yii\db\Migration
{
    const COMMUNITY = 'community';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('models_classname',['table' => 'community'],['classname' => 'open20\amos\community\models\Community']);
        $this->update('models_classname',['table' => 'user'],['classname' => 'open20\amos\core\user\User']);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
}
