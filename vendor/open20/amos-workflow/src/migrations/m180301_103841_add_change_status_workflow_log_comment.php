<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\workflow\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m180301_103841_add_change_status_workflow_log_comment
 */
class m180301_103841_add_change_status_workflow_log_comment extends Migration
{

    const TABLE = '{{%amos_workflow_transitions_log}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'comment', $this->text()->defaultValue(null)->after('end_status')->comment('Change status comment'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'comment');
        return true;
    }

}
