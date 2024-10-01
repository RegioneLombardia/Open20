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

class m220502_123200_add_default_not_show_again extends Migration
{

    const TABLE = '{{%slideshow}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'default_not_show_again', $this->boolean()->defaultValue(false)->after('eval_contoller_method')->comment(''));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'default_not_show_again');
        return true;
    }

}
