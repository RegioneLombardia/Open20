<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\workflow\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

class m220411_160141_add_evaluate_method_field extends Migration{
    
    const TABLE = '{{%slideshow}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'eval_contoller_method', $this->text(255)->defaultValue(null)->after('description')->comment(''));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'eval_contoller_method');
        return true;
    }
}
